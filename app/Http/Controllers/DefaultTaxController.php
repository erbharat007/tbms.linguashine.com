<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultTaxRequest;
use App\Models\Currency;
use App\Models\DefaultTax;
use App\Models\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DefaultTaxController extends Controller
{
    /**
     * List the default taxes set for clients and vendors.
     */
    public function index(): View
    {
        // Ordered by the linked tax's sort order, grouped by object type for tabs.
        $all = DefaultTax::with(['tax', 'currency'])
            ->join('taxes', 'taxes.id', '=', 'default_taxes.tax_id')
            ->orderBy('taxes.sort_order')
            ->orderBy('taxes.name')
            ->select('default_taxes.*')
            ->get()
            ->groupBy('object_type');

        return view('settings.default-taxes.index', [
            'objectTypes' => DefaultTax::objectTypes(),
            'grouped' => $all,
        ]);
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        return view('settings.default-taxes.create', array_merge(
            ['defaultTax' => null],
            $this->formData(),
        ));
    }

    /**
     * Store a new default tax.
     */
    public function store(DefaultTaxRequest $request): RedirectResponse
    {
        DefaultTax::create($request->defaultTaxData());

        return redirect()->route('default-taxes.index')
            ->with('success', 'Default tax added.');
    }

    /**
     * Show the edit form.
     */
    public function edit(DefaultTax $defaultTax): View
    {
        return view('settings.default-taxes.edit', array_merge(
            ['defaultTax' => $defaultTax],
            $this->formData(),
        ));
    }

    /**
     * Update a default tax.
     */
    public function update(DefaultTaxRequest $request, DefaultTax $defaultTax): RedirectResponse
    {
        $defaultTax->update($request->defaultTaxData());

        return redirect()->route('default-taxes.index')
            ->with('success', 'Default tax updated.');
    }

    /**
     * Delete a default tax.
     */
    public function destroy(DefaultTax $defaultTax): RedirectResponse
    {
        $defaultTax->delete();

        return redirect()->route('default-taxes.index')
            ->with('success', 'Default tax deleted.');
    }

    /**
     * Shared data for the create/edit form.
     *
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        // Taxes grouped by type, for the dynamic (type-filtered) Tax dropdown.
        $taxesByType = Tax::orderBy('name')->get()
            ->groupBy('type')
            ->map(fn ($group) => $group->map(fn ($tax) => ['id' => $tax->id, 'name' => $tax->name])->values());

        return [
            'objectTypes' => DefaultTax::objectTypes(),
            'taxTypes' => Tax::types(),
            'taxesByType' => $taxesByType,
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')
                ->get()->map(fn ($c) => ['id' => $c->id, 'label' => $c->code.' - '.$c->name]),
        ];
    }
}
