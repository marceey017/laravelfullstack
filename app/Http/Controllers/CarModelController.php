<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarModelController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'manufacturer_id' => ['nullable', 'integer', 'exists:manufacturers,id'],
        ]);

        $search = trim((string) $request->input('search', ''));
        $manufacturerId = $request->filled('manufacturer_id') ? (int) $request->input('manufacturer_id') : null;

        $carModels = CarModel::query()
            ->with('manufacturer')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($manufacturerId !== null, fn ($query) => $query->where('manufacturer_id', $manufacturerId))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $manufacturers = $this->manufacturerOptions();

        return view('car_models.index', compact('carModels', 'manufacturers', 'search', 'manufacturerId'));
    }

    public function create(): View
    {
        $manufacturers = $this->manufacturerOptions();

        return view('car_models.create', compact('manufacturers'));
    }

    public function store(Request $request): RedirectResponse
    {
        CarModel::create($this->validateCarModel($request));

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
        $manufacturers = $this->manufacturerOptions();

        return view('car_models.edit', compact('carModel', 'manufacturers'));
    }

    public function update(Request $request, CarModel $carModel): RedirectResponse
    {
        $carModel->update($this->validateCarModel($request, $carModel));

        return redirect()->route('car_models.index')
            ->with('success', 'Az autómodell frissült.');
    }

    public function destroy(CarModel $carModel): RedirectResponse
    {
        $carModel->delete();

        return redirect()->route('car_models.index')
            ->with('success', 'Az autómodell törlődött.');
    }

    private function validateCarModel(Request $request, ?CarModel $carModel = null): array
    {
        $uniqueName = Rule::unique('car_models', 'name')
            ->where('manufacturer_id', $request->input('manufacturer_id'));

        if ($carModel) {
            $uniqueName->ignore($carModel->id);
        }

        return $request->validate([
            'manufacturer_id' => ['required', 'integer', 'exists:manufacturers,id'],
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'release_year' => ['required', 'integer', 'between:1886,'.now()->year],
        ]);
    }

    private function manufacturerOptions(): Collection
    {
        return Manufacturer::query()->orderBy('name')->get(['id', 'name']);
    }
}
