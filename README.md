# Autókatalógus — Laravel fullstack iskolai projekt

Téma: autógyártók és autómodellek. Laravel 12, két kapcsolódó adattábla, később Bootstrap nézetek. A két tábla backend CRUD-ja elkészült. A frontendes következő feladata az összes Blade nézet és a Bootstrap megjelenítés.

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

Windows alatt `cp` helyett `copy` használandó. A `php artisan test` tesztek SQLite in-memory adatbázison futnak, így a tesztekhez nem kell a helyi MySQL. A Blade nézeteket a frontendes készíti, addig a listázó oldalak nem jelennek meg.

## Adatmodell

- `manufacturers`: `id`, kötelező `name` és `country`, időbélyegek. A gyártónév a gyártói űrlapon egyedi.
- `car_models`: `id`, kötelező `manufacturer_id`, `name`, `release_year`, időbélyegek.
- Egy gyártóhoz sok modell tartozik. Gyártó törlése a modelljeit is törli.
- A `/` átirányít a `/manufacturers` oldalra. Mindkét tábla resource útvonalai és keresése készen vannak; az autómodellek `?manufacturer_id=` paraméterrel gyártóra is szűrhetők.

## Csapatmunka

Minden tag a saját GitHub-fiókjából commitoljon külön branche-re. A git szerzőnevének átírása önmagában nem igazolja a tényleges fiókhozzáférést. Ellenőrizzétek a GitHubon, hogy a tanár mindhárom szerző commitjait látja.

- Marci: Laravel alap, migrációk, modellek és kapcsolatok, gyártók backend CRUD és keresés, tesztadatok.
- Második backendes: modell CRUD, keresés és gyártó szerinti szűrés, validáció és tesztek.
- Frontendes: minden gyártó és modell Blade oldal, Bootstrap, kereső/szűrő/űrlapok, hiba- és sikerüzenetek.

A frontend feladatának átadását a [FRONTEND_HANDOFF.md](docs/FRONTEND_HANDOFF.md) tartalmazza.
