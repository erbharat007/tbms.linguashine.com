<?php

namespace App\Http\Controllers;

use App\Models\VendorPortalSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorApplicationController extends Controller
{
    /**
     * Public vendor application form (no auth).
     *
     * Reached via the shareable link on the Vendor portal settings screen:
     *   /vendor-app/create?token=<application_token>
     *
     * The full registration form is deferred; for now we validate the token
     * and the "form enabled" flag, then show a placeholder landing page so the
     * URL is live and shareable.
     */
    public function create(Request $request): View
    {
        $token = $request->string('token')->toString();

        $settings = VendorPortalSetting::query()
            ->where('application_token', $token)
            ->first();

        // Invalid/expired token, or the form has been switched off.
        abort_if($settings === null || ! $settings->application_form_enabled, 404);

        return view('vendor-app.create', [
            'settings' => $settings,
        ]);
    }
}
