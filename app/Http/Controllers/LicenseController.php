<?php

namespace App\Http\Controllers;

use App\Models\LicenseSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    /**
     * Display the license information.
     */
    public function index(): View
    {
        $license = LicenseSetting::current();
        $activeManagers = User::where('is_active', true)->where('is_manager', true)->count();
        $totalUsers = User::count();

        // Calculate remaining time
        $remainingDays = null;
        if ($license->expires_at) {
            $remainingDays = max(0, Carbon::today()->diffInDays(Carbon::parse($license->expires_at), false));
        }

        return view('settings.license.index', compact('license', 'activeManagers', 'totalUsers', 'remainingDays'));
    }

    /**
     * Update or renew the license key.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'license_key' => 'required|string|max:255',
        ]);

        $license = LicenseSetting::current();
        $license->update([
            'license_key' => trim($validated['license_key']),
            'status' => 'Active',
            'expires_at' => now()->addYears(2),
        ]);

        return redirect()->route('license.index')->with('status', 'License key validated and updated successfully.');
    }
}
