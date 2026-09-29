<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            ['Volkswagen', 'Németország', [['Golf', 1974], ['Passat', 1973]]],
            ['BMW', 'Németország', [['3-as sorozat', 1975], ['5-ös sorozat', 1972]]],
            ['Toyota', 'Japán', [['Corolla', 1966], ['Yaris', 1999]]],
        ];

        foreach ($cars as [$name, $country, $models]) {
            $manufacturer = Manufacturer::firstOrCreate(
                ['name' => $name],
                ['country' => $country]
            );

            foreach ($models as [$modelName, $startYear]) {
                CarModel::firstOrCreate(
                    ['manufacturer_id' => $manufacturer->id, 'name' => $modelName],
                    ['release_year' => $startYear]
                );
            }
        }
    }
}
