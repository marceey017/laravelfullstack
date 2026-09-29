# Átadás a második backendesnek

Folytasd a `marceey017/laravelfullstack` autós Laravel 12 projektet a saját GitHub-fiókoddal. Húzd le a friss `main` ágat, készíts saját `backend/car-models` branche-t, és csak a rád bízott backend részt készítsd el. Ne csinálj frontendet: ne írj vagy módosíts Blade, CSS, JS, Tailwind vagy Bootstrap fájlokat.

Már kész a két migráció, az Eloquent modellek kapcsolata, a `ManufacturerController` CRUD és a `manufacturers` resource route. Olvasd el a README-t, és tartsd meg a mezőneveket és a Laravel konvenciókat.

## Feladatod

1. Készíts `app/Http/Controllers/CarModelController.php` fájlt teljes resource CRUD-dal: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
2. A `routes/web.php` fájlban add hozzá: `Route::resource('car_models', CarModelController::class);`, a szükséges importtal. A route prefix pontosan `car_models` legyen.
3. Az `index` gyártóval listázzon (`with('manufacturer')`), név szerinti kereséssel `?search=...` és szülő szerinti szűréssel `?manufacturer_id=...`. A két szűrő egyszerre is működjön. Az eredmény legyen név szerint rendezett és lapozott, `withQueryString()` használatával. A nézet adatai: `$carModels`, `$manufacturers`, `$search`, `$manufacturerId`.
4. Nézetnevek: `car_models.index`, `car_models.create`, `car_models.show`, `car_models.edit`. A create/edit kapjon `$manufacturers`-t; az edit/show kapjon `$carModel`-t. Ezeket a nézetfájlokat ne hozd létre.
5. A `store` és `update` ellenőrizze: létező `manufacturer_id`, kötelező `name` legfeljebb 255 karakter, kötelező egész `release_year` 1886 és az aktuális év között. A `manufacturer_id` + `name` pár legyen egyedi a validációban, frissítésnél a saját rekord kivételével. A már létező migrációkat és mezőneveket ne írd át.
6. Sikeres mentés és törlés után irányíts a `car_models.index` oldalra, és adj magyar flash üzenetet.
7. Írj feature teszteket in-memory SQLite-tal: létrehozás, módosítás, törlés, validáció, valamint névkeresés és gyártószűrés együtt. Ha a Blade nézet még hiányzik, az index tesztjénél vizsgáld a view adatát a nézet renderelését megkerülve, vagy futtasd a teljes HTTP tesztet a frontend elkészülése után. Futtasd a teljes tesztkészletet.

Commitolj a saját GitHub-fiókoddal, pushold a branche-t, és nyiss PR-t a `main` felé. A PR-ban írd le, mely teszteket futtattad, és hogy a nézetek még a frontendes feladatai. A gyártók backendjét és a migrációkat ne írd át ok nélkül.
