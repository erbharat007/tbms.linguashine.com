<?php

namespace App\Http\Controllers;

use App\Models\AccountSetting;
use App\Models\CatLogTemplate;
use App\Models\Currency;
use App\Models\LegalEntity;
use App\Models\Timezone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountSettingController extends Controller
{
    /**
     * Display the account details and system settings.
     */
    public function index(): View
    {
        $account = AccountSetting::current();
        $legalEntities = LegalEntity::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $catLogTemplates = CatLogTemplate::orderBy('name')->get();
        $timezones = Timezone::orderBy('name')->get();

        return view('settings.account.index', compact(
            'account',
            'legalEntities',
            'currencies',
            'catLogTemplates',
            'timezones'
        ));
    }

    /**
     * Update account details and system settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'timezone' => 'required|string|max:100',
            'legal_entity_id' => 'nullable|exists:legal_entities,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'client_cat_log_template_id' => 'nullable|exists:cat_log_templates,id',
            'vendor_cat_log_template_id' => 'nullable|exists:cat_log_templates,id',
            'default_quote_pdf_template' => 'nullable|string|max:100',
            'default_client_invoice_pdf_template' => 'nullable|string|max:100',
            'default_vendor_invoice_pdf_template' => 'nullable|string|max:100',
            'default_job_po_pdf_template' => 'nullable|string|max:100',
        ]);

        $account = AccountSetting::current();
        $account->update($validated);

        if (!empty($validated['currency_id'])) {
            // Also update is_system on currencies table
            Currency::query()->update(['is_system' => false]);
            Currency::where('id', $validated['currency_id'])->update(['is_system' => true]);
        }

        return redirect()->route('account.index')->with('status', 'Account settings updated successfully.');
    }

    /**
     * Regenerate the API key.
     */
    public function regenerateApiKey(): RedirectResponse
    {
        $account = AccountSetting::current();
        $account->update(['api_key' => Str::random(40)]);

        return redirect()->route('account.index')->with('status', 'API key regenerated successfully.');
    }
}
