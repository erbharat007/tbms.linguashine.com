<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxController extends Controller
{
    /**
     * List all taxes (ordered as they appear inside invoices).
     */
    public function index(): View
    {
        return view('settings.taxes.index', [
            'taxes' => Tax::orderBy('sort_order')->orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        return view('settings.taxes.create', [
            'tax' => null,
        ]);
    }

    /**
     * Store a new tax.
     */
    public function store(TaxRequest $request): RedirectResponse
    {
        Tax::create($request->validated());

        return redirect()->route('taxes.index')
            ->with('success', 'Tax created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Tax $tax): View
    {
        return view('settings.taxes.edit', [
            'tax' => $tax,
        ]);
    }

    /**
     * Update a tax.
     */
    public function update(TaxRequest $request, Tax $tax): RedirectResponse
    {
        $tax->update($request->validated());

        return redirect()->route('taxes.index')
            ->with('success', 'Tax updated.');
    }

    /**
     * Delete a tax.
     */
    public function destroy(Tax $tax): RedirectResponse
    {
        $tax->delete();

        return redirect()->route('taxes.index')
            ->with('success', 'Tax deleted.');
    }
}
