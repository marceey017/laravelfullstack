<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarModelController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'manufacturer_id' => ['nullable', 'integer', 'exists:manufacturers,id'],
        ]);

        $search = trim($filters['search'] ?? '');
        $manufacturerId = isset($filters['manufacturer_id'])
            ? (int) $filters['manufacturer_id']
            : null;

        $manufacturers = Manufacturer::orderBy('name')->get();
        $carModels = CarModel::with('manufacturer')
            ->filterListing($search, $manufacturerId)
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('car_models.index', compact(
            'carModels',
            'manufacturers',
            'search',
            'manufacturerId'
        ));
    }

    public function create(): View
    {
        $manufacturers = Manufacturer::orderBy('name')->get();

        return view('car_models.create', compact('manufacturers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'manufacturer_id' => ['required', 'integer', 'exists:manufacturers,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_models', 'name')
                    ->where('manufacturer_id', $request->input('manufacturer_id')),
            ],
            'release_year' => ['required', 'integer', 'min:1886', 'max:'.date('Y')],
        ]);

        CarModel::create($data);

        return redirect()->route('car_models.index')
            ->with('success', 'Az autómodell létrejött.');
    }

    public function show(CarModel $carModel): View
    {
        $carModel->load('manufacturer');

        return view('car_models.show', compact('carModel'));
    }

    public function edit(CarModel $carModel): View
    {
        $manufacturers = Manufacturer::orderBy('name')->get();

        return view('car_models.edit', compact('carModel', 'manufacturers'));
    }

    public function update(Request $request, CarModel $carModel): RedirectResponse
    {
        $data = $request->validate([
            'manufacturer_id' => ['required', 'integer', 'exists:manufacturers,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_models', 'name')
                    ->where('manufacturer_id', $request->input('manufacturer_id'))
                    ->ignore($carModel->id),
            ],
            'release_year' => ['required', 'integer', 'min:1886', 'max:'.date('Y')],
        ]);

        $carModel->update($data);

        return redirect()->route('car_models.index')
            ->with('success', 'Az autómodell frissült.');
    }

    public function destroy(CarModel $carModel): RedirectResponse
    {
        $carModel->delete();

        return redirect()->route('car_models.index')
            ->with('success', 'Az autómodell törlődött.');
    }
}
