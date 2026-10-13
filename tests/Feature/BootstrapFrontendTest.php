<?php

namespace Tests\Feature;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BootstrapFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_eight_pages_render_the_bootstrap_layout(): void
    {
        $manufacturer = Manufacturer::create(['name' => 'Volkswagen', 'country' => 'Németország']);
        $carModel = CarModel::create(['manufacturer_id' => $manufacturer->id, 'name' => 'Golf', 'release_year' => 1974]);

        $urls = [
            route('manufacturers.index'),
            route('manufacturers.create'),
            route('manufacturers.show', $manufacturer),
            route('manufacturers.edit', $manufacturer),
            route('car_models.index'),
            route('car_models.create'),
            route('car_models.show', $carModel),
            route('car_models.edit', $carModel),
        ];

        foreach ($urls as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('bootstrap@5.3.8/dist/css/bootstrap.min.css', false)
                ->assertSee('aria-label="Fő navigáció"', false)
                ->assertSee('id="content"', false)
                ->assertDontSee('border="1"', false);
        }
    }

    public function test_model_search_and_filter_survive_bootstrap_pagination(): void
    {
        $manufacturer = Manufacturer::create(['name' => 'Volkswagen', 'country' => 'Németország']);
        $other = Manufacturer::create(['name' => 'BMW', 'country' => 'Németország']);

        for ($i = 0; $i < 12; $i++) {
            CarModel::create([
                'manufacturer_id' => $manufacturer->id,
                'name' => sprintf('Touring %02d', $i),
                'release_year' => 2000,
            ]);
        }

        CarModel::create(['manufacturer_id' => $other->id, 'name' => 'Touring idegen', 'release_year' => 2000]);
        $filters = ['search' => 'Touring', 'manufacturer_id' => $manufacturer->id];

        $this->get(route('car_models.index', $filters))
            ->assertOk()
            ->assertSee('table-responsive', false)
            ->assertSee('class="pagination', false)
            ->assertSee('value="Touring"', false)
            ->assertSee('value="'.$manufacturer->id.'" selected', false)
            ->assertSee('search=Touring', false)
            ->assertSee('manufacturer_id='.$manufacturer->id, false)
            ->assertSee('page=2', false)
            ->assertDontSeeText('Touring idegen');

        $this->get(route('car_models.index', [...$filters, 'page' => 2]))
            ->assertOk()
            ->assertSeeText('Touring 10')
            ->assertDontSeeText('Touring 00')
            ->assertSee('value="Touring"', false);
    }

    public function test_manufacturer_search_survives_bootstrap_pagination(): void
    {
        for ($i = 0; $i < 12; $i++) {
            Manufacturer::create(['name' => sprintf('Teszt %02d', $i), 'country' => 'Magyarország']);
        }

        $this->get(route('manufacturers.index', ['search' => 'Teszt']))
            ->assertOk()
            ->assertSee('class="pagination', false)
            ->assertSee('search=Teszt', false)
            ->assertSee('page=2', false)
            ->assertSee('value="Teszt"', false);

        $this->get(route('manufacturers.index', ['search' => 'Teszt', 'page' => 2]))
            ->assertOk()
            ->assertSeeText('Teszt 10')
            ->assertDontSeeText('Teszt 00');
    }

    public function test_manufacturer_validation_shows_inline_errors_and_old_input(): void
    {
        $this->from(route('manufacturers.create'))->post(route('manufacturers.store'), [
            'name' => '',
            'country' => 'Magyarország',
        ])->assertSessionHasErrors('name');

        $this->get(route('manufacturers.create'))
            ->assertOk()
            ->assertSee('is-invalid', false)
            ->assertSee('class="invalid-feedback"', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('value="Magyarország"', false)
            ->assertSee('role="alert"', false);
    }

    public function test_model_validation_preserves_the_selected_manufacturer(): void
    {
        $manufacturer = Manufacturer::create(['name' => 'Volkswagen', 'country' => 'Németország']);

        $this->from(route('car_models.create'))->post(route('car_models.store'), [
            'manufacturer_id' => $manufacturer->id,
            'name' => 'Golf',
            'release_year' => 1800,
        ])->assertSessionHasErrors('release_year');

        $this->get(route('car_models.create'))
            ->assertOk()
            ->assertSee('value="'.$manufacturer->id.'" selected', false)
            ->assertSee('value="Golf"', false)
            ->assertSee('id="year-error"', false)
            ->assertSee('is-invalid', false);
    }

    public function test_empty_states_and_success_alert_are_rendered(): void
    {
        $this->get(route('manufacturers.index'))->assertOk()->assertSeeText('Nincs találat.');
        $this->get(route('car_models.index'))->assertOk()->assertSeeText('Nincs találat.');
        $this->get(route('car_models.create'))
            ->assertOk()
            ->assertSeeText('Előbb hozz létre egy gyártót.')
            ->assertSee('alert alert-warning', false);

        $this->withSession(['success' => 'Sikeres mentés.'])->get(route('manufacturers.index'))
            ->assertOk()
            ->assertSee('alert alert-success', false)
            ->assertSeeText('Sikeres mentés.');
    }

    public function test_record_names_are_escaped_in_the_title_and_page(): void
    {
        $name = '</title><script>alert(1)</script>';
        $manufacturer = Manufacturer::create(['name' => $name, 'country' => 'Magyarország']);

        $this->get(route('manufacturers.show', $manufacturer))
            ->assertOk()
            ->assertSee(e($name), false)
            ->assertDontSee($name, false);
    }
}
