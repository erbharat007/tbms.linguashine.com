<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::orderBy('name')->paginate(20);

        return view('reference.units.index', compact('units'));
    }

    public function create(): View
    {
        return view('reference.units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Unit::create($this->validated($request));

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit): RedirectResponse
    {
        return redirect()->route('units.edit', $unit);
    }

    public function edit(Unit $unit): View
    {
        return view('reference.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $unit->update($this->validated($request, $unit));

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Toggle a unit's enabled state.
     */
    public function toggle(Unit $unit): RedirectResponse
    {
        $unit->update(['is_enabled' => ! $unit->is_enabled]);

        return redirect()->route('units.index')
            ->with('success', 'Unit ' . ($unit->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    /**
     * Validate the request for storing/updating a unit.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Unit $unit = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('units', 'name')->ignore($unit)],
            'is_word' => ['boolean'],
            'is_enabled' => ['boolean'],
        ]);

        $data['is_word'] = $request->boolean('is_word');
        $data['is_enabled'] = $request->boolean('is_enabled');

        return $data;
    }
}
