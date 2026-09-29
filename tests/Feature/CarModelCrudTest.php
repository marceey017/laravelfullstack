<?php

namespace Tests\Feature;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class CarModelCrudTest extends TestCase
{
    use RefreshDatabase;

    private string $stubViewPath;

    private Manufacturer $volkswagen;

    private Manufacturer $toyota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViewPath = sys_get_temp_dir().'/car_model_view_stubs_'.getmypid();

        foreach (['index', 'create', 'show', 'edit'] as $view) {
            File::ensureDirectoryExists($this->stubViewPath.'/car_models');
            File::put($this->stubViewPath."/car_models/{$view}.blade.php", '');
        }

        View::addLocation($this->stubViewPath);

        $this->volkswagen = Manufacturer::create(['name' => 'Volkswagen', 'country' => 'Németország']);
        $this->toyota = Manufacturer::create(['name' => 'Toyota', 'country' => 'Japán']);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->stubViewPath);

        parent::tearDown();
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'manufacturer_id' => $this->volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ], $overrides);
    }

    public function test_car_model_can_be_created(): void
    {
        $this->post(route('car_models.store'), $this->validData())
            ->assertRedirect(route('car_models.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('car_models', [
            'manufacturer_id' => $this->volkswagen->id,
            'name' => 'Golf',
            'release_year' => 1974,
        ]);
    }

    public function test_car_model_can_be_updated(): void
    {
        $carModel = CarModel::create($this->validData());

        $this->put(route('car_models.update', $carModel), $this->validData([
            'manufacturer_id' => $this->toyota->id,
            'name' => 'Corolla',
            'release_year' => 1966,
        ]))
            ->assertRedirect(route('car_models.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('car_models', [
            'id' => $carModel->id,
            'manufacturer_id' => $this->toyota->id,
            'name' => 'Corolla',
            'release_year' => 1966,
        ]);
    }

    public function test_car_model_can_be_updated_without_changing_its_name(): void
    {
        $carModel = CarModel::create($this->validData());

        $this->put(route('car_models.update', $carModel), $this->validData(['release_year' => 1975]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('car_models.index'));

        $this->assertSame(1975, (int) $carModel->fresh()->release_year);
    }

    public function test_car_model_can_be_deleted(): void
    {
        $carModel = CarModel::create($this->validData());

        $this->delete(route('car_models.destroy', $carModel))
            ->assertRedirect(route('car_models.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('car_models', ['id' => $carModel->id]);
        $this->assertDatabaseHas('manufacturers', ['id' => $this->volkswagen->id]);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->post(route('car_models.store'), [])
            ->assertSessionHasErrors(['manufacturer_id', 'name', 'release_year']);

        $this->assertSame(0, CarModel::count());
    }

    public function test_manufacturer_must_exist(): void
    {
        $this->post(route('car_models.store'), $this->validData(['manufacturer_id' => 9999]))
            ->assertSessionHasErrors('manufacturer_id');

        $this->assertSame(0, CarModel::count());
    }

    public function test_name_cannot_be_longer_than_255_characters(): void
    {
        $this->post(route('car_models.store'), $this->validData(['name' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('name');
    }

    public function test_release_year_must_be_realistic(): void
    {
        foreach ([1885, now()->year + 1, 'nem szám', 1999.5] as $year) {
            $this->post(route('car_models.store'), $this->validData(['release_year' => $year]))
                ->assertSessionHasErrors('release_year');
        }

        foreach ([1886, now()->year] as $year) {
            $this->post(route('car_models.store'), $this->validData(['name' => "Modell {$year}", 'release_year' => $year]))
                ->assertSessionHasNoErrors();
        }

        $this->assertSame(2, CarModel::count());
    }

    public function test_name_must_be_unique_within_manufacturer(): void
    {
        CarModel::create($this->validData());

        $this->post(route('car_models.store'), $this->validData(['release_year' => 1980]))
            ->assertSessionHasErrors('name');

        $this->post(route('car_models.store'), $this->validData(['manufacturer_id' => $this->toyota->id]))
            ->assertSessionHasNoErrors();

        $this->assertSame(2, CarModel::count());
    }

    public function test_update_rejects_name_taken_by_other_model_of_same_manufacturer(): void
    {
        CarModel::create($this->validData());
        $passat = CarModel::create($this->validData(['name' => 'Passat', 'release_year' => 1973]));

        $this->put(route('car_models.update', $passat), $this->validData(['name' => 'Golf']))
            ->assertSessionHasErrors('name');

        $this->assertSame('Passat', $passat->fresh()->name);
    }

    public function test_index_passes_expected_view_data(): void
    {
        CarModel::create($this->validData());

        $this->get(route('car_models.index'))
            ->assertOk()
            ->assertViewIs('car_models.index')
            ->assertViewHas('carModels', fn ($carModels) => $carModels->total() === 1
                && $carModels->first()->relationLoaded('manufacturer'))
            ->assertViewHas('manufacturers', fn ($manufacturers) => $manufacturers->pluck('name')->all() === ['Toyota', 'Volkswagen'])
            ->assertViewHas('search', '')
            ->assertViewHas('manufacturerId', null);
    }

    public function test_index_filters_by_search_and_manufacturer_together(): void
    {
        CarModel::create($this->validData(['name' => 'Golf']));
        CarModel::create($this->validData(['name' => 'Golf Plus', 'release_year' => 2005]));
        CarModel::create($this->validData(['name' => 'Passat', 'release_year' => 1973]));
        CarModel::create($this->validData(['manufacturer_id' => $this->toyota->id, 'name' => 'Golf Cart', 'release_year' => 2000]));

        $this->get(route('car_models.index', ['search' => 'golf']))
            ->assertViewHas('carModels', fn ($carModels) => $carModels->total() === 3);

        $this->get(route('car_models.index', ['manufacturer_id' => $this->volkswagen->id]))
            ->assertViewHas('carModels', fn ($carModels) => $carModels->total() === 3);

        $this->get(route('car_models.index', ['search' => 'golf', 'manufacturer_id' => $this->volkswagen->id]))
            ->assertViewHas('carModels', fn ($carModels) => $carModels->pluck('name')->all() === ['Golf', 'Golf Plus'])
            ->assertViewHas('search', 'golf')
            ->assertViewHas('manufacturerId', $this->volkswagen->id);
    }

    public function test_index_paginates_and_keeps_filters_in_links(): void
    {
        foreach (range(1, 12) as $i) {
            CarModel::create($this->validData(['name' => sprintf('Modell %02d', $i)]));
        }

        $this->get(route('car_models.index', ['search' => 'Modell', 'manufacturer_id' => $this->volkswagen->id]))
            ->assertViewHas('carModels', function ($carModels) {
                $nextPage = $carModels->nextPageUrl();

                return $carModels->count() === 10
                    && $carModels->total() === 12
                    && $carModels->first()->name === 'Modell 01'
                    && str_contains($nextPage, 'search=Modell')
                    && str_contains($nextPage, 'manufacturer_id='.$this->volkswagen->id)
                    && str_contains($nextPage, 'page=2');
            });
    }

    public function test_index_rejects_unknown_manufacturer_filter(): void
    {
        $this->get(route('car_models.index', ['manufacturer_id' => 9999]))
            ->assertSessionHasErrors('manufacturer_id');
    }

    public function test_create_show_and_edit_pass_expected_view_data(): void
    {
        $carModel = CarModel::create($this->validData());

        $this->get(route('car_models.create'))
            ->assertOk()
            ->assertViewIs('car_models.create')
            ->assertViewHas('manufacturers', fn ($manufacturers) => $manufacturers->count() === 2);

        $this->get(route('car_models.show', $carModel))
            ->assertOk()
            ->assertViewIs('car_models.show')
            ->assertViewHas('carModel', fn ($model) => $model->is($carModel) && $model->relationLoaded('manufacturer'));

        $this->get(route('car_models.edit', $carModel))
            ->assertOk()
            ->assertViewIs('car_models.edit')
            ->assertViewHas('carModel', fn ($model) => $model->is($carModel))
            ->assertViewHas('manufacturers', fn ($manufacturers) => $manufacturers->count() === 2);
    }

    public function test_missing_car_model_returns_404(): void
    {
        $this->get(route('car_models.show', 9999))->assertNotFound();
    }
}
