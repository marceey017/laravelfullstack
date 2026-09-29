# Átadás a frontendesnek

Folytasd a `marceey017/laravelfullstack` autós Laravel 12 projektet a saját GitHub-fiókoddal. Húzd le a friss `main` ágat, készíts saját `frontend/views` branche-t, és csak a Blade nézeteket készítsd el. Ne módosíts controllert, route-ot, modellt, migrációt, tesztet, configot vagy más PHP fájlt.

A backend kész: a `manufacturers` és a `car_models` resource route-ok, a két controller teljes CRUD-dal, kereséssel, szűréssel, validációval és flash üzenetekkel. Olvasd el a README-t, és tartsd meg a route- és változóneveket.

## Általános szabályok

- Csak a `resources/views` alatt dolgozz.
- Bootstrap 5-öt CDN-ről töltsd be a layoutban (CSS és JS bundle). Ne használj `@vite`-ot, Tailwindet vagy npm-et, a projekt XAMPP alatt build nélkül fut.
- Egyedi CSS és JS csak ha muszáj, elsősorban Bootstrap osztályokat használj.
- Ne írj kommenteket se Blade, se HTML formában.
- A felület szövegei magyarul legyenek.

## Feladatod

1. Készíts `resources/views/layouts/app.blade.php` layoutot Bootstrap navbarral, amely a `manufacturers.index` és a `car_models.index` oldalra linkel. Itt jelenjen meg a `session('success')` flash üzenet zöld, bezárható alertként.
2. Készítsd el a nézeteket: `manufacturers.index`, `manufacturers.create`, `manufacturers.edit`, `manufacturers.show`, `car_models.index`, `car_models.create`, `car_models.edit`, `car_models.show`. A közös űrlaprészeket kiszervezheted `_form.blade.php` fájlokba.
3. `manufacturers.index`: a nézet adatai `$manufacturers` (lapozott, 10/oldal, minden elemnél `car_models_count`) és `$search`. Kell GET kereső űrlap a `search` mezővel, amely megtartja az értéket, "Szűrés törlése" link, táblázat (név, ország, modellek száma, műveletek), "Új gyártó" gomb, üres lista esetén üzenet, lapozás: `{{ $manufacturers->links('pagination::bootstrap-5') }}`.
4. `manufacturers.create` nem kap változót, az `edit` a `$manufacturer`-t kapja. Mezők: kötelező `name` (max 255, egyedi) és kötelező `country` (max 255).
5. `manufacturers.show`: a `$manufacturer` betöltött `carModels` kapcsolattal. Mutasd a gyártó adatait és a modelljeit (név, kiadási év, link a modellre), valamint a szerkesztés és törlés gombot.
6. `car_models.index`: a nézet adatai `$carModels` (lapozott, 10/oldal, betöltött `manufacturer`), `$manufacturers` (név szerint rendezve, `id` és `name`), `$search` és `$manufacturerId` (int vagy null). Kell GET űrlap `search` szövegmezővel és `manufacturer_id` legördülővel ("Összes gyártó" üres értékkel, a kiválasztott gyártó maradjon kijelölve). A két szűrő együtt is működik. Kell még "Szűrés törlése" link, táblázat (név, gyártó, kiadási év, műveletek), "Új modell" gomb, üres lista esetén üzenet, lapozás: `{{ $carModels->links('pagination::bootstrap-5') }}`. Érvénytelen gyártószűrőnél a backend validációs hibával visszairányít, ezért itt is jelenítsd meg a `$errors`-t.
7. `car_models.create` a `$manufacturers`-t, az `edit` a `$carModel`-t és a `$manufacturers`-t kapja. Mezők: `manufacturer_id` legördülő (kötelező), `name` (kötelező, max 255, gyártón belül egyedi), `release_year` number input `min="1886"` és `max="{{ now()->year }}"` értékkel.
8. `car_models.show`: a `$carModel` betöltött `manufacturer` kapcsolattal. A gyártó neve linkeljen a gyártó oldalára, legyen szerkesztés és törlés gomb.
9. Minden űrlapon legyen `@csrf`, frissítésnél `@method('PUT')`. Hibánál maradjon meg a beírt érték (`old()`, a legördülőnél is), a hibás mező kapjon `is-invalid` osztályt, alatta `@error` blokk `invalid-feedback` osztállyal. Legyen "Mégse" vagy "Vissza" link a listára.
10. A törlés POST űrlap legyen `@method('DELETE')`-tel és `confirm()` megerősítéssel. Gyártó törlésénél figyelmeztess, hogy a modelljei is törlődnek.
11. Reszponzív, egyszerű Bootstrap felület legyen: `container`, `card`, `table table-striped table-hover`, a táblázat körül `table-responsive`, a táblázatban `btn-sm` gombok.

## Ellenőrzés

Futtasd a `php artisan migrate --seed` és a `php artisan serve` parancsot, és kattintsd végig az összes oldalt: listázás, keresés, szűrés, lapozás, létrehozás, hibás űrlap, szerkesztés, törlés. Futtasd a `php artisan test` parancsot is. A backend tesztjei a valódi nézeteket is renderelik, ezért a nézetek nem dobhatnak hibát a fenti változókkal, üres listánál és `null` értékű `$manufacturerId`-nél sem.

Commitolj a saját GitHub-fiókoddal, pushold a branche-t, és nyiss PR-t a `main` felé. A PR-ban írd le, mely oldalakat próbáltad ki, és hogy lefutott-e a teljes tesztkészlet.
