<?php

namespace App\Http\Controllers;

use App\Models\ClientPortalSetting;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPortalSettingController extends Controller
{
    /**
     * Show the current client portal settings.
     */
    public function show(): View
    {
        return view('settings.client-portal.show', [
            'settings' => ClientPortalSetting::current()->load([
                'defaultPaymentMethod',
                'defaultClientManager',
                'notificationRecipient',
            ]),
        ]);
    }

    /**
     * Show the client portal settings form (also the "Set default payment method" form).
     */
    public function edit(): View
    {
        return view('settings.client-portal.edit', [
            'settings' => ClientPortalSetting::current(),
            'paymentMethods' => PaymentMethod::where('is_enabled', true)->orderBy('name')->pluck('name', 'id'),
            'managers' => User::managers()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Persist the single client portal settings row.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'default_payment_terms' => ['required', 'integer', 'min:0', 'max:365'],
            'allow_clients_create_projects' => ['boolean'],
            'default_client_manager_id' => ['required', 'exists:users,id'],
            'notification_recipient_id' => ['required', 'exists:users,id'],
        ]);

        $validated['allow_clients_create_projects'] = $request->boolean('allow_clients_create_projects');

        ClientPortalSetting::current()->update($validated);

        return redirect()->route('client-portal.show')
            ->with('success', 'Client portal settings saved.');
    }
}
