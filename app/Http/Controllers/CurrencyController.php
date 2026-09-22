<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function index(): View
    {
        $currencies = Currency::orderBy('code')->paginate(20);

        return view('reference.currencies.index', compact('currencies'));
    }

    public function create(): View
    {
        return view('reference.currencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Currency::create($this->validated($request));

        return redirect()->route('currencies.index')
            ->with('success', 'Currency created successfully.');
    }

    public function show(Currency $currency): RedirectResponse
    {
        return redirect()->route('currencies.edit', $currency);
    }

    public function edit(Currency $currency): View
    {
        return view('reference.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $currency->update($this->validated($request, $currency));

        return redirect()->route('currencies.index')
            ->with('success', 'Currency updated successfully.');
    }

    /**
     * Toggle a currency's enabled state. The system currency stays enabled.
     */
    public function toggle(Currency $currency): RedirectResponse
    {
        if ($currency->is_system && $currency->is_enabled) {
            return redirect()->route('currencies.index')
                ->with('error', 'The system currency cannot be disabled.');
        }

        $currency->update(['is_enabled' => ! $currency->is_enabled]);

        return redirect()->route('currencies.index')
            ->with('success', 'Currency ' . ($currency->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    /**
     * Set a currency as the system (base) currency. Only one at a time.
     */
    public function setSystem(Currency $currency): RedirectResponse
    {
        DB::transaction(function () use ($currency) {
            Currency::where('is_system', true)->update(['is_system' => false]);
            $currency->update(['is_system' => true, 'is_enabled' => true]);
        });

        return redirect()->route('currencies.index')
            ->with('success', "{$currency->code} is now the system currency.");
    }

    public function destroy(Currency $currency): RedirectResponse
    {
        if ($currency->is_system) {
            return redirect()->route('currencies.index')
                ->with('error', 'The system currency cannot be removed.');
        }

        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('success', 'Currency removed.');
    }

    /**
     * Validate the request for storing/updating a currency.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Currency $currency = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:3', Rule::unique('currencies', 'code')->ignore($currency)],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['nullable', 'string', 'max:10'],
            'is_enabled' => ['boolean'],
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_enabled'] = $request->boolean('is_enabled');

        return $data;
    }
}
