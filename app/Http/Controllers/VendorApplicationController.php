<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\VendorPortalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorApplicationController extends Controller
{
    /**
     * Public vendor application form (no auth).
     *
     * Reached via the shareable link on the Vendor portal settings screen:
     *   /vendor-app/create?token=<application_token>
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

    /**
     * Admin: Listing of vendor applications (Vendors > Applications).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $status = $request->string('status')->trim();

        $applications = VendorApplication::query()
            ->with(['country', 'vendor', 'reviewer'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('application_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('native_language', 'like', "%{$search}%");
                });
            })
            ->when($status->isNotEmpty(), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('vendors.applications.index', [
            'applications' => $applications,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Admin: Show application details.
     */
    public function show(VendorApplication $vendorApplication): View
    {
        $vendorApplication->load(['country', 'vendor', 'reviewer']);

        return view('vendors.applications.show', [
            'application' => $vendorApplication,
        ]);
    }

    /**
     * Admin: Approve application and convert into an active Vendor.
     */
    public function approve(Request $request, VendorApplication $vendorApplication): RedirectResponse
    {
        if ($vendorApplication->status === 'approved') {
            return back()->with('info', 'Application has already been approved.');
        }

        DB::transaction(function () use ($vendorApplication, $request) {
            $nextCode = 'VEND-' . str_pad((Vendor::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

            $vendor = Vendor::create([
                'vendor_code' => $nextCode,
                'name' => $vendorApplication->name,
                'type' => $vendorApplication->type,
                'status' => 'active',
                'country_id' => $vendorApplication->country_id,
                'note' => "Approved from Application #{$vendorApplication->application_number}. " . $vendorApplication->note,
            ]);

            // Create primary contact
            $parts = explode(' ', $vendorApplication->name, 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? 'Translator';

            $vendor->contacts()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $vendorApplication->email,
                'phone' => $vendorApplication->phone,
                'is_primary' => true,
            ]);

            $vendorApplication->update([
                'status' => 'approved',
                'vendor_id' => $vendor->id,
                'reviewed_by' => $request->user()?->id,
                'reviewed_at' => now(),
            ]);
        });

        return redirect()->route('vendor-applications.index')
            ->with('success', 'Application approved and vendor record created successfully.');
    }

    /**
     * Admin: Reject application.
     */
    public function reject(Request $request, VendorApplication $vendorApplication): RedirectResponse
    {
        $vendorApplication->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('vendor-applications.index')
            ->with('success', 'Application marked as rejected.');
    }

    /**
     * Admin: Delete application.
     */
    public function destroy(VendorApplication $vendorApplication): RedirectResponse
    {
        $vendorApplication->delete();

        return redirect()->route('vendor-applications.index')
            ->with('success', 'Application deleted successfully.');
    }
}
