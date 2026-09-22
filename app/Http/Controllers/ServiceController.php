<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('name')->paginate(20);

        return view('reference.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('reference.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validated($request));

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service): RedirectResponse
    {
        return redirect()->route('services.edit', $service);
    }

    public function edit(Service $service): View
    {
        return view('reference.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request, $service));

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Toggle a service's enabled state.
     */
    public function toggle(Service $service): RedirectResponse
    {
        $service->update(['is_enabled' => ! $service->is_enabled]);

        return redirect()->route('services.index')
            ->with('success', 'Service ' . ($service->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Validate the request for storing/updating a service.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($service)],
            'is_enabled' => ['boolean'],
        ]);

        $data['is_enabled'] = $request->boolean('is_enabled');

        return $data;
    }
}
