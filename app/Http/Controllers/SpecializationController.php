<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecializationController extends Controller
{
    public function index(): View
    {
        $specializations = Specialization::orderBy('name')->paginate(20);

        return view('settings.specializations.index', compact('specializations'));
    }

    public function create(): View
    {
        return view('settings.specializations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Specialization::create($this->validated($request));

        return redirect()->route('specializations.index')
            ->with('success', 'Specialization created successfully.');
    }

    public function show(Specialization $specialization): RedirectResponse
    {
        return redirect()->route('specializations.edit', $specialization);
    }

    public function edit(Specialization $specialization): View
    {
        return view('settings.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization): RedirectResponse
    {
        $specialization->update($this->validated($request, $specialization));

        return redirect()->route('specializations.index')
            ->with('success', 'Specialization updated successfully.');
    }

    /**
     * Toggle a specialization's enabled state.
     */
    public function toggle(Specialization $specialization): RedirectResponse
    {
        $specialization->update(['is_enabled' => ! $specialization->is_enabled]);

        return redirect()->route('specializations.index')
            ->with('success', 'Specialization ' . ($specialization->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function destroy(Specialization $specialization): RedirectResponse
    {
        $specialization->delete();

        return redirect()->route('specializations.index')
            ->with('success', 'Specialization deleted successfully.');
    }

    /**
     * Validate the request for storing/updating a specialization.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Specialization $specialization = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('specializations', 'name')->ignore($specialization)],
            'is_enabled' => ['boolean'],
        ]);

        $data['is_enabled'] = $request->boolean('is_enabled');

        return $data;
    }
}
