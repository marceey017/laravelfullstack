const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');

(async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    const base = process.env.APP_URL || 'http://127.0.0.1:8000';
    const manufacturerName = 'Bootstrap böngészőteszt';
    const modelName = 'Bootstrap tesztmodell';
    fs.mkdirSync('artifacts/bootstrap', { recursive: true });

    async function visit(path) {
        const response = await page.goto(new URL(path, base).href);
        assert.equal(response.status(), 200, path);
        await page.waitForFunction(() => getComputedStyle(document.body).display === 'flex');
    }

    async function save() {
        await Promise.all([
            page.waitForURL(url => !url.pathname.endsWith('/create') && !url.pathname.endsWith('/edit')),
            page.getByRole('button', { name: 'Mentés', exact: true }).click(),
        ]);
        await page.locator('.alert-success').waitFor();
    }

    try {
        await visit('/manufacturers/create');
        await page.getByLabel('Név', { exact: true }).fill(manufacturerName);
        await page.getByLabel('Ország', { exact: true }).fill('Magyarország');
        await save();
        await visit('/manufacturers?search=' + encodeURIComponent(manufacturerName));
        const manufacturerRow = page.locator('tbody tr').filter({ hasText: manufacturerName });
        const manufacturerShow = await manufacturerRow.getByRole('link', { name: 'Megnézés' }).getAttribute('href');
        const manufacturerEdit = await manufacturerRow.getByRole('link', { name: 'Szerkesztés' }).getAttribute('href');
        await visit(manufacturerEdit);
        await page.getByLabel('Ország', { exact: true }).fill('Magyarország – teszt');
        await save();

        await visit('/car_models/create');
        await page.getByLabel('Gyártó', { exact: true }).selectOption({ label: manufacturerName });
        const manufacturerId = await page.getByLabel('Gyártó', { exact: true }).inputValue();
        await page.getByLabel('Név', { exact: true }).fill(modelName);
        await page.getByLabel('Megjelenési év', { exact: true }).fill('2000');
        await save();
        await visit('/car_models');
        await page.getByLabel('Keresés', { exact: true }).fill(modelName);
        await page.getByLabel('Gyártó', { exact: true }).selectOption(manufacturerId);
        await Promise.all([
            page.waitForURL(url => url.searchParams.get('search') === modelName),
            page.getByRole('button', { name: 'Szűrés', exact: true }).click(),
        ]);
        assert.equal(await page.locator('tbody tr').count(), 1);
        assert.equal(await page.getByLabel('Gyártó', { exact: true }).inputValue(), manufacturerId);
        const modelRow = page.locator('tbody tr').filter({ hasText: modelName });
        const modelShow = await modelRow.getByRole('link', { name: 'Megnézés' }).getAttribute('href');
        const modelEdit = await modelRow.getByRole('link', { name: 'Szerkesztés' }).getAttribute('href');
        await visit(modelEdit);
        await page.getByLabel('Megjelenési év', { exact: true }).fill('2001');
        await save();
        await visit(modelShow);
        assert.match(await page.locator('main').innerText(), /2001/);

        const pages = [
            ['/manufacturers', 'manufacturers-index'],
            ['/manufacturers/create', 'manufacturers-create'],
            [manufacturerShow, 'manufacturers-show'],
            [manufacturerEdit, 'manufacturers-edit'],
            ['/car_models', 'models-index'],
            ['/car_models/create', 'models-create'],
            [modelShow, 'models-show'],
            [modelEdit, 'models-edit'],
        ];
        for (const width of [1440, 375]) {
            await page.setViewportSize({ width, height: 1000 });
            for (const [path, name] of pages) {
                await visit(path);
                assert.equal(await page.locator('h1').count(), 1, name);
                const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth + 1);
                assert.equal(overflow, false, `${name}: horizontal page overflow at ${width}px`);
                await page.screenshot({ path: `artifacts/bootstrap/${name}-${width}.png`, fullPage: true });
                console.log(`PASS ${name} ${width}px (HTTP 200, Bootstrap loaded, no page overflow)`);
            }
        }

        await visit('/car_models?search=' + encodeURIComponent(modelName));
        let dialogMessage = '';
        page.once('dialog', async dialog => { dialogMessage = dialog.message(); await dialog.dismiss(); });
        await page.locator('tbody tr').filter({ hasText: modelName }).getByRole('button', { name: 'Törlés', exact: true }).click();
        assert.match(dialogMessage, /Biztosan/);
        assert.equal(await page.locator('tbody tr').filter({ hasText: modelName }).count(), 1);
        page.once('dialog', dialog => dialog.accept());
        await Promise.all([
            page.waitForURL(url => url.search === ''),
            page.locator('tbody tr').filter({ hasText: modelName }).getByRole('button', { name: 'Törlés', exact: true }).click(),
        ]);
        await page.locator('.alert-success').waitFor();
        await visit('/car_models?search=' + encodeURIComponent(modelName));
        assert.equal(await page.locator('tbody tr').count(), 0);

        await visit('/manufacturers?search=' + encodeURIComponent(manufacturerName));
        page.once('dialog', dialog => { assert.match(dialog.message(), /összes hozzá tartozó modellt/); return dialog.accept(); });
        await Promise.all([
            page.waitForURL(url => url.search === ''),
            page.locator('tbody tr').filter({ hasText: manufacturerName }).getByRole('button', { name: 'Törlés', exact: true }).click(),
        ]);
        await page.locator('.alert-success').waitFor();
        await visit('/manufacturers?search=' + encodeURIComponent(manufacturerName));
        assert.equal(await page.locator('tbody tr').count(), 0);
        assert.deepEqual(errors, []);
        console.log('PASS browser CRUD, combined search/filter, cancel/confirm deletion, 16 responsive page checks, no JavaScript exceptions');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
