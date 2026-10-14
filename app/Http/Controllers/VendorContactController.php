<?php

namespace App\Http\Controllers;

use App\Models\Timezone;
use App\Models\Vendor;
use App\Models\VendorContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorContactController extends Controller
{
    /**
     * Cross-vendor contact persons list (Vendors > Contacts).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $vendorId = $request->integer('vendor_id');

        $contacts = VendorContact::query()
            ->with(['vendor.country', 'timezone'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('vendor', function ($vq) use ($search) {
                            $vq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($vendorId > 0, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('vendors.contacts.index', [
            'contacts' => $contacts,
            'search' => $search,
            'vendorId' => $vendorId,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show vendor specific contacts.
     */
    public function vendorContacts(Vendor $vendor): View
    {
        $contacts = $vendor->contacts()->with('timezone')->get();

        return view('vendors.contacts.vendor-index', [
            'vendor' => $vendor,
            'contacts' => $contacts,
        ]);
    }

    /**
     * Show create contact form.
     */
    public function create(Request $request): View
    {
        return view('vendors.contacts.create', [
            'contact' => new VendorContact(['vendor_id' => $request->integer('vendor_id')]),
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'timezones' => Timezone::orderBy('label')->pluck('label', 'id'),
        ]);
    }

    /**
     * Store new contact person.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'skype' => ['nullable', 'string', 'max:100'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'gender' => ['nullable', 'in:male,female'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['is_primary'])) {
            VendorContact::where('vendor_id', $data['vendor_id'])->update(['is_primary' => false]);
        }

        VendorContact::create($data);

        return redirect()->route('vendor-contacts.index')
            ->with('success', 'Contact person added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorContact $contact): View
    {
        return view('vendors.contacts.edit', [
            'contact' => $contact,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'timezones' => Timezone::orderBy('label')->pluck('label', 'id'),
        ]);
    }

    /**
     * Update contact person.
     */
    public function update(Request $request, VendorContact $contact): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'skype' => ['nullable', 'string', 'max:100'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'gender' => ['nullable', 'in:male,female'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['is_primary'])) {
            VendorContact::where('vendor_id', $data['vendor_id'])
                ->where('id', '!=', $contact->id)
                ->update(['is_primary' => false]);
        }

        $contact->update($data);

        return redirect()->route('vendor-contacts.index')
            ->with('success', 'Contact person updated successfully.');
    }

    /**
     * Delete contact person.
     */
    public function destroy(VendorContact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('vendor-contacts.index')
            ->with('success', 'Contact person deleted successfully.');
    }
}
