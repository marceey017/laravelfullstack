# Autókatalógus — Laravel fullstack iskolai projekt

Autógyártók és autómodellek kezelése Laravel 12-vel, két kapcsolódó adattáblával és Bootstrap 5.3.8-as Blade felülettel. Mindkét tábla létrehozó, listázó, részletező, szerkesztő és törlő funkciója elkészült. A listák kereshetők, az autómodellek gyártóra is szűrhetők.

## Indítás XAMPP mellett

PHP 8.2+ és Composer szükséges. XAMPP-ban indítsd el a MySQL-t, majd phpMyAdminban hozz létre egy `laravelfullstack` nevű adatbázist. Ha a helyi root jelszó vagy port eltér, módosítsd a `.env` fájlban.

```bash
git clone https://github.com/marceey017/laravelfullstack.git
cd laravelfullstack
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Windows alatt `cp` helyett `copy` használandó. Már létező projekt frissítéséhez először mentsd el a saját módosításaidat, majd a `main` ágon futtasd a `git pull --ff-only` és a `php artisan view:clear` parancsot.

## Bootstrap felület

- Közös navigáció aktív menüponttal, mobilon tördelődő elrendezés.
- Reszponzív táblázatok, Bootstrap gombok, kártyák és űrlapok.
- Mezőnkénti validációs hibák, megőrzött űrlapértékek és sikerüzenetek.
- Keresés és gyártószűrés együtt; a szűrők lapozáskor is megmaradnak.
- Magyar feliratú Bootstrap-lapozó, üres állapotok és megerősített törlés. A gyártó törlése előtt külön figyelmeztetés jelzi a modellek törlését is.

A Bootstrap CSS verziórögzített, integritásellenőrzött jsDelivr CDN-ről töltődik be. **A formázáshoz internetkapcsolat kell.** Az alkalmazás indításához nem kell Node.js, npm vagy Vite-build; a Bootstrap felület nem használ külön JavaScript-csomagot.

## Ellenőrzés

```bash
php artisan test
php artisan view:cache
vendor/bin/pint --test
```

A PHP-tesztek SQLite in-memory adatbázison futnak, ezért nem módosítják a helyi MySQL-adatokat. Az új frontendtesztek mind a nyolc oldalt ténylegesen renderelik, és ellenőrzik a Bootstrap-betöltést jelölő HTML-t, a lapozást, a megőrzött szűrőket, a validációs visszajelzéseket és a biztonságos szövegmegjelenítést.

A GitHub Actions `Laravel frontend checks` munkafolyamata ezen felül külön tesztadatbázison valódi Chromium-böngészővel ellenőrzi a létrehozást, módosítást, keresést, szűrést és törlést, valamint mind a nyolc oldalt 1440 és 375 pixel szélességen. A képernyőképek a futás `bootstrap-screenshots` artifactjában találhatók. Ez a böngészőteszt használ Node.js-t és Playwrightot; a normál helyi alkalmazásindítás nem igényli őket.

## Adatmodell

- `manufacturers`: `id`, kötelező `name` és `country`, időbélyegek. A gyártónév egyedi.
- `car_models`: `id`, kötelező `manufacturer_id`, `name`, `release_year`, időbélyegek. Az évszám 1886 és az aktuális év közötti egész szám.
- Egy gyártóhoz sok modell tartozik. Gyártó törlése a modelljeit is törli.
- A `/` átirányít a `/manufacturers` oldalra. Az autómodellek listája a `/car_models` útvonalon érhető el.

## Csapatmunka

Minden tag a saját GitHub-fiókjából commitoljon külön branche-re. A git szerzőnevének átírása önmagában nem igazolja a tényleges fiókhozzáférést. Ellenőrizzétek a GitHubon, hogy a tanár mindhárom szerző commitjait látja.

- Marci: Laravel alap, migrációk, modellek és kapcsolatok, gyártók backend CRUD és keresés, tesztadatok.
- Második backendes: modell CRUD, keresés és gyártó szerinti szűrés, validáció és tesztek.
- Frontendes: minden gyártó és modell Blade oldal, Bootstrap, kereső/szűrő/űrlapok, hiba- és sikerüzenetek.

Az eredeti frontendkövetelmények a [FRONTEND_HANDOFF.md](docs/FRONTEND_HANDOFF.md) dokumentumban olvashatók.
