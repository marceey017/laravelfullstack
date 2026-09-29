<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $search = trim($request->input('search', ''));

        $manufacturers = Manufacturer::query()
            ->withCount('carModels')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('manufacturers.index', compact('manufacturers', 'search'));
    }

    public function create(): View
    {
        return view('manufacturers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:manufacturers,name'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        Manufacturer::create($data);

        return redirect()->route('manufacturers.index')
            ->with('success', 'Az autógyártó létrejött.');
    }

    public function show(Manufacturer $manufacturer): View
    {
        $manufacturer->load('carModels');

        return view('manufacturers.show', compact('manufacturer'));
    }

    public function edit(Manufacturer $manufacturer): View
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    public function update(Request $request, Manufacturer $manufacturer): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('manufacturers', 'name')->ignore($manufacturer->id)],
            'country' => ['required', 'string', 'max:255'],
        ]);

        $manufacturer->update($data);

        return redirect()->route('manufacturers.index')
            ->with('success', 'Az autógyártó frissült.');
    }

    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        $manufacturer->delete();

        return redirect()->route('manufacturers.index')
            ->with('success', 'Az autógyártó és a hozzá tartozó modellek törlődtek.');
    }
}
