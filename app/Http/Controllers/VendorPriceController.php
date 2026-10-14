<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\LanguagePair;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorPriceController extends Controller
{
    /**
     * List all vendor prices (Vendors > Prices).
     */
    public function index(Request $request): View
    {
        $vendorId = $request->integer('vendor_id');

        $prices = VendorPrice::with([
            'vendor.country', 'service', 'languagePair.sourceLanguage',
            'languagePair.targetLanguage', 'specialization', 'unit', 'currency', 'updatedBy',
        ])
        ->when($vendorId > 0, function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

        return view('vendors.prices.index', [
            'prices' => $prices,
            'vendorId' => $vendorId,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show create form.
     */
    public function create(Request $request): View
    {
        return view('vendors.prices.create', array_merge(
            ['price' => new VendorPrice(['vendor_id' => $request->integer('vendor_id') ?: null])],
            $this->formData(),
        ));
    }

    /**
     * Store new price.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'language_pair_id' => ['required', 'exists:language_pairs,id'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'note' => ['nullable', 'string'],
        ]);

        $data['updated_by'] = $request->user()?->id;

        VendorPrice::create($data);

        return redirect()->route('vendor-prices.index')
            ->with('success', 'Vendor price created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorPrice $vendorPrice): View
    {
        return view('vendors.prices.edit', array_merge(
            ['price' => $vendorPrice],
            $this->formData(),
        ));
    }

    /**
     * Update price.
     */
    public function update(Request $request, VendorPrice $vendorPrice): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'language_pair_id' => ['required', 'exists:language_pairs,id'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'note' => ['nullable', 'string'],
        ]);

        $data['updated_by'] = $request->user()?->id;

        $vendorPrice->update($data);

        return redirect()->route('vendor-prices.index')
            ->with('success', 'Vendor price updated successfully.');
    }

    /**
     * Delete price.
     */
    public function destroy(VendorPrice $vendorPrice): RedirectResponse
    {
        $vendorPrice->delete();

        return redirect()->route('vendor-prices.index')
            ->with('success', 'Vendor price deleted successfully.');
    }

    /**
     * Shared dropdown options.
     */
    private function formData(): array
    {
        return [
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'services' => Service::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'languagePairs' => LanguagePair::where('is_enabled', true)
                ->with(['sourceLanguage', 'targetLanguage'])
                ->get(),
            'specializations' => Specialization::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'units' => Unit::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->get(['id', 'code', 'name']),
        ];
    }
}
