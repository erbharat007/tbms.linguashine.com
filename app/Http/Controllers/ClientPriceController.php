<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientPriceRequest;
use App\Models\ClientPrice;
use App\Models\Currency;
use App\Models\LanguagePair;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientPriceController extends Controller
{
    /**
     * List all client prices.
     */
    public function index(): View
    {
        return view('clients.prices.index', [
            'prices' => ClientPrice::with([
                'client.country', 'service', 'languagePair.sourceLanguage',
                'languagePair.targetLanguage', 'specialization', 'unit', 'currency', 'updatedBy',
            ])->latest()->paginate(20),
        ]);
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        return view('clients.prices.create', array_merge(
            ['price' => null],
            $this->formData(),
        ));
    }

    /**
     * Store a new price.
     */
    public function store(ClientPriceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        ClientPrice::create($data);

        return redirect()->route('client-prices.index')
            ->with('success', 'Price created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(ClientPrice $clientPrice): View
    {
        return view('clients.prices.edit', array_merge(
            ['price' => $clientPrice],
            $this->formData(),
        ));
    }

    /**
     * Update a price.
     */
    public function update(ClientPriceRequest $request, ClientPrice $clientPrice): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $clientPrice->update($data);

        return redirect()->route('client-prices.index')
            ->with('success', 'Price updated.');
    }

    /**
     * Delete a price.
     */
    public function destroy(ClientPrice $clientPrice): RedirectResponse
    {
        $clientPrice->delete();

        return redirect()->route('client-prices.index')
            ->with('success', 'Price deleted.');
    }

    /**
     * Shared dropdown data for the create/edit form (enabled reference data).
     *
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'services' => Service::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'languagePairs' => LanguagePair::where('is_enabled', true)
                ->with(['sourceLanguage', 'targetLanguage'])->get()
                ->sortBy(fn ($p) => $p->label)->values(),
            'specializations' => Specialization::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'units' => Unit::where('is_enabled', true)->orderBy('name')->get(['id', 'name']),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->get(['id', 'code', 'name']),
        ];
    }
}
