<?php

namespace Tests\Feature;

use App\Http\Controllers\CarModelController;
use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CarModelCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_model_can_be_created_updated_and_deleted(): void
    {
        $volkswagen = Manufacturer::create([
            'name' => 'Volkswagen',
            'country' => 'Németország',
        ]);
        $bmw = Manufacturer::create([
            'name' => 'BMW',
            'country' => 'Németország',
        ]);

        $this->post(route('car_models.store'), [
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ])->assertRedirect(route('car_models.index'));

        $carModel = CarModel::where('name', 'Golf')->firstOrFail();
        $this->assertTrue($carModel->manufacturer->is($volkswagen));

        $this->put(route('car_models.update', $carModel), [
            'manufacturer_id' => $bmw->id,
            'name' => '3-as sorozat',
            'release_year' => 1975,
        ])->assertRedirect(route('car_models.index'));

        $this->assertDatabaseHas('car_models', [
            'id' => $carModel->id,
            'manufacturer_id' => $bmw->id,
            'name' => '3-as sorozat',
            'release_year' => 1975,
        ]);

        $this->delete(route('car_models.destroy', $carModel))
            ->assertRedirect(route('car_models.index'));

        $this->assertDatabaseMissing('car_models', ['id' => $carModel->id]);
    }

    public function test_invalid_and_duplicate_data_is_rejected(): void
    {
        $volkswagen = Manufacturer::create([
            'name' => 'Volkswagen',
            'country' => 'Németország',
        ]);
        $bmw = Manufacturer::create([
            'name' => 'BMW',
            'country' => 'Németország',
        ]);
        $golf = CarModel::create([
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ]);

        $this->post(route('car_models.store'), [
            'manufacturer_id' => 9999,
            'name' => 'Új modell',
            'release_year' => 2000,
        ])->assertSessionHasErrors('manufacturer_id');

        $this->post(route('car_models.store'), [
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 2020,
        ])->assertSessionHasErrors('name');

        $this->post(route('car_models.store'), [
            'manufacturer_id' => $bmw->id,
            'name' => 'Golf',
            'release_year' => 2020,
        ])->assertRedirect(route('car_models.index'));

        $this->put(route('car_models.update', $golf), [
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ])->assertRedirect(route('car_models.index'));

        $this->post(route('car_models.store'), [
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Jövőbeli modell',
            'release_year' => (int) date('Y') + 1,
        ])->assertSessionHasErrors('release_year');

        $this->assertSame(2, CarModel::count());
    }

    public function test_name_search_and_manufacturer_filter_work_together(): void
    {
        $volkswagen = Manufacturer::create([
            'name' => 'Volkswagen',
            'country' => 'Németország',
        ]);
        $bmw = Manufacturer::create([
            'name' => 'BMW',
            'country' => 'Németország',
        ]);

        $golf = CarModel::create([
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ]);
        CarModel::create([
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Passat',
            'release_year' => 1973,
        ]);
        CarModel::create([
            'manufacturer_id' => $bmw->id,
            'name' => 'Golf',
            'release_year' => 2020,
        ]);

        $matches = CarModel::filterListing('Golf', $volkswagen->id)
            ->pluck('id')
            ->all();

        $this->assertSame([$golf->id], $matches);
        $this->assertSame(2, CarModel::filterListing('Golf', null)->count());
        $this->assertSame(2, CarModel::filterListing('', $volkswagen->id)->count());
    }

    public function test_index_passes_filtered_data_to_the_frontend_without_rendering_it(): void
    {
        $volkswagen = Manufacturer::create([
            'name' => 'Volkswagen',
            'country' => 'Németország',
        ]);
        $bmw = Manufacturer::create([
            'name' => 'BMW',
            'country' => 'Németország',
        ]);

        $wanted = CarModel::create([
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ]);
        CarModel::create([
            'manufacturer_id' => $volkswagen->id,
            'name' => 'Passat',
            'release_year' => 1973,
        ]);
        CarModel::create([
            'manufacturer_id' => $bmw->id,
            'name' => 'Golf',
            'release_year' => 2020,
        ]);

        $temporaryViewRoot = storage_path('framework/testing-views');
        $temporaryViewDirectory = $temporaryViewRoot.DIRECTORY_SEPARATOR.'car_models';
        $temporaryViewFile = $temporaryViewDirectory.DIRECTORY_SEPARATOR.'index.blade.php';

        File::ensureDirectoryExists($temporaryViewDirectory);
        File::put($temporaryViewFile, '');
        view()->addLocation($temporaryViewRoot);

        try {
            $request = Request::create('/car_models', 'GET', [
                'search' => 'Golf',
                'manufacturer_id' => $volkswagen->id,
            ]);
            $view = app(CarModelController::class)->index($request);
            $data = $view->getData();

            $this->assertSame('car_models.index', $view->name());
            $this->assertSame('Golf', $data['search']);
            $this->assertSame($volkswagen->id, $data['manufacturerId']);
            $this->assertSame([$wanted->id], $data['carModels']->pluck('id')->all());
            $this->assertCount(2, $data['manufacturers']);
            $this->assertTrue($data['carModels']->first()->relationLoaded('manufacturer'));
        } finally {
            File::deleteDirectory($temporaryViewRoot);
        }
    }
}
