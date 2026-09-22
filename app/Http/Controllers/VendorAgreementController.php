<?php

namespace App\Http\Controllers;

use App\Models\VendorPortalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorAgreementController extends Controller
{
    /**
     * Show the "new agreement" form.
     */
    public function create(): View
    {
        return view('settings.vendor-agreement.create', [
            'settings' => VendorPortalSetting::current(),
        ]);
    }

    /**
     * Store the agreement text on the vendor portal singleton.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAgreement($request);

        VendorPortalSetting::current()->saveAgreement($validated['agreement_text'], $request->user()->id);

        return redirect()->route('vendor-portal.show')
            ->with('success', 'Vendor agreement created.');
    }

    /**
     * Show the "edit agreement" form.
     */
    public function edit(): View
    {
        return view('settings.vendor-agreement.edit', [
            'settings' => VendorPortalSetting::current(),
        ]);
    }

    /**
     * Update the agreement text.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $this->validateAgreement($request);

        VendorPortalSetting::current()->saveAgreement($validated['agreement_text'], $request->user()->id);

        return redirect()->route('vendor-portal.show')
            ->with('success', 'Vendor agreement updated.');
    }

    /**
     * Remove the agreement.
     */
    public function destroy(): RedirectResponse
    {
        VendorPortalSetting::current()->clearAgreement();

        return redirect()->route('vendor-portal.show')
            ->with('success', 'Vendor agreement deleted.');
    }

    /**
     * Public vendor agreement page (no auth), reached via the token URL.
     */
    public function view(Request $request): View
    {
        $token = $request->string('token')->toString();

        $settings = VendorPortalSetting::query()
            ->where('application_token', $token)
            ->first();

        abort_if($settings === null || ! $settings->hasAgreement(), 404);

        return view('vendor-agreement.view', [
            'settings' => $settings,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateAgreement(Request $request): array
    {
        return $request->validate([
            'agreement_text' => ['required', 'string'],
        ]);
    }
}
