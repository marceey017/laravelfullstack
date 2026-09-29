<?php

namespace Tests\Feature;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManufacturerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_manufacturer_can_be_created_updated_and_deleted(): void
    {
        $this->post(route('manufacturers.store'), [
            'name' => 'Volkswagen',
            'country' => 'Németország',
        ])->assertRedirect(route('manufacturers.index'));

        $manufacturer = Manufacturer::where('name', 'Volkswagen')->firstOrFail();
        $model = CarModel::create([
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ]);

        $this->assertTrue($manufacturer->carModels->contains($model));
        $this->assertTrue($model->manufacturer->is($manufacturer));

        $this->put(route('manufacturers.update', $manufacturer), [
            'name' => 'VW',
            'country' => 'Németország',
        ])->assertRedirect(route('manufacturers.index'));

        $this->assertDatabaseHas('manufacturers', ['id' => $manufacturer->id, 'name' => 'VW']);

        $this->delete(route('manufacturers.destroy', $manufacturer))
            ->assertRedirect(route('manufacturers.index'));

        $this->assertDatabaseMissing('manufacturers', ['id' => $manufacturer->id]);
        $this->assertDatabaseMissing('car_models', ['id' => $model->id]);
    }

    public function test_duplicate_manufacturer_name_is_rejected(): void
    {
        Manufacturer::create(['name' => 'BMW', 'country' => 'Németország']);

        $this->post(route('manufacturers.store'), [
            'name' => 'BMW',
            'country' => 'Németország',
        ])
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Manufacturer::count());
    }
}
