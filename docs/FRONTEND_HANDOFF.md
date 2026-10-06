# Átadás a frontendesnek

A két Laravel backend CRUD elkészült. A saját GitHub-fiókoddal, külön branchen dolgozz, majd PR-t nyiss a main felé. A feladat Bootstrap vagy Tailwind használatával elkészíteni a szerveroldali Blade felületet. A modelleket, migrációkat, validációt és controller útvonalakat ne írd át indokolatlanul.

## Elkészítendő nézetek

- Közös layout: navigáció a gyártók és modellek között, sikerüzenet és validációs hibák.
- Gyártók: manufacturers.index, manufacturers.create, manufacturers.show, manufacturers.edit.
- Modellek: car_models.index, car_models.create, car_models.show, car_models.edit.
- Kereső mindkét indexen: GET mező neve search. A modellek indexén gyártóválasztó: GET mező neve manufacturer_id. A kereső és a szűrő egyszerre is működjön; a szűrőértékek maradjanak láthatók.
- Listákon linkek az új rekordhoz, részletekhez és szerkesztéshez, valamint törlő űrlap megerősítéssel. A lapozást jelenítsd meg.
- Minden POST űrlapba @csrf; frissítéshez @method('PUT'), törléshez @method('DELETE').

## Controller által átadott adatok

- manufacturers.index: $manufacturers lapozó (car_models_count mezővel) és $search.
- manufacturers.create: nincs külön adat. manufacturers.show/edit: $manufacturer; a show a carModels kapcsolatot is betölti.
- car_models.index: $carModels lapozó a manufacturer kapcsolattal, $manufacturers, $search, $manufacturerId.
- car_models.create: $manufacturers. car_models.edit: $carModel és $manufacturers. car_models.show: $carModel betöltött manufacturer kapcsolattal.
- A gyártó űrlap mezői: name, country. A modell űrlap mezői: manufacturer_id, name, release_year. A release_year kötelező egész évszám 1886 és az aktuális év között.

Az oldalakat a php artisan serve paranccsal ellenőrizd; a korábban elkészült php artisan test teszteket is futtasd. A saját commitjaidat a saját GitHub-fiókodhoz kösd, mert a tanár szerzőnként értékeli a munkát.
