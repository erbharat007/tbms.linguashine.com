<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorPortalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorPortalSettingController extends Controller
{
    /**
     * Show the current vendor portal settings.
     */
    public function show(): View
    {
        return view('settings.vendor-portal.show', [
            'settings' => VendorPortalSetting::current()->load([
                'invoiceNotificationRecipient',
                'applicationNotificationRecipient',
            ]),
        ]);
    }

    /**
     * Show the vendor portal settings form.
     */
    public function edit(): View
    {
        return view('settings.vendor-portal.edit', [
            'settings' => VendorPortalSetting::current(),
            'managers' => User::managers()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Persist the single vendor portal settings row.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_payment_terms' => ['required', 'integer', 'min:0', 'max:365'],
            'allow_vendors_create_invoices' => ['boolean'],
            'invoice_notification_recipient_id' => ['required', 'exists:users,id'],
            'application_form_enabled' => ['boolean'],
            'application_notification_recipient_id' => ['required', 'exists:users,id'],
        ]);

        $validated['allow_vendors_create_invoices'] = $request->boolean('allow_vendors_create_invoices');
        $validated['application_form_enabled'] = $request->boolean('application_form_enabled');

        VendorPortalSetting::current()->update($validated);

        return redirect()->route('vendor-portal.show')
            ->with('success', 'Vendor portal settings saved.');
    }

    /**
     * Regenerate the public application form token (invalidates the old URL).
     */
    public function regenerateToken(): RedirectResponse
    {
        VendorPortalSetting::current()->regenerateToken();

        return redirect()->route('vendor-portal.show')
            ->with('success', 'Vendor application form URL regenerated. The previous link no longer works.');
    }
}
