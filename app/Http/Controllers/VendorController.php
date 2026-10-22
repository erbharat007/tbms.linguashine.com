<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\LegalEntity;
use App\Models\Timezone;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorContact;
use App\Models\VendorTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $type = $request->string('type')->trim();
        $status = $request->string('status')->trim();
        $assignable = $request->query('assignable');
        $canLogin = $request->query('can_login');

        $vendors = Vendor::query()
            ->with(['country', 'currency', 'primaryContact', 'tags'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('vendor_code', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($type->isNotEmpty(), function ($query) use ($type) {
                if ($type === 'freelancer' || $type === 'individual') {
                    $query->whereIn('type', ['freelancer', 'individual']);
                } else {
                    $query->where('type', $type);
                }
            })
            ->when($status->isNotEmpty(), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($assignable !== null && $assignable !== '', function ($query) use ($assignable) {
                $query->where('is_assignable', (bool) $assignable);
            })
            ->when($canLogin !== null && $canLogin !== '', function ($query) use ($canLogin) {
                $query->whereHas('primaryContact', function ($q) use ($canLogin) {
                    $q->where('can_login', (bool) $canLogin);
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('vendors.index', [
            'vendors' => $vendors,
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'assignable' => $assignable,
            'canLogin' => $canLogin,
        ]);
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create(): View
    {
        return view('vendors.create', $this->formData() + [
            'vendor' => new Vendor([
                'type' => 'freelancer',
                'status' => 'active',
                'is_assignable' => true,
                'can_access_finances' => true,
            ]),
            'contact' => null,
            'selectedTags' => [],
        ]);
    }

    /**
     * Store a newly created vendor and its primary contact.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:individual,freelancer,company,in-house'],
            'status' => ['required', 'in:active,inactive,pending,blocked'],
            'legal_entity_id' => ['nullable', 'exists:legal_entities,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'payment_terms' => ['nullable', 'integer', 'min:0'],
            'region' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'vat_number' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'is_assignable' => ['nullable', 'boolean'],
            'can_access_finances' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:vendor_tags,id'],
            // Primary contact fields
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'skype' => ['nullable', 'string', 'max:100'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'gender' => ['nullable', 'in:male,female'],
            'can_login' => ['nullable', 'boolean'],
        ]);

        $vendor = DB::transaction(function () use ($request, $data) {
            $nextCode = 'VEND-' . str_pad((Vendor::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

            $vendor = Vendor::create([
                'vendor_code' => $nextCode,
                'name' => $data['name'],
                'type' => $data['type'],
                'status' => $data['status'],
                'legal_entity_id' => $data['legal_entity_id'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'currency_id' => $data['currency_id'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'payment_terms' => $data['payment_terms'] ?? 0,
                'region' => $data['region'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'tax_number' => $data['tax_number'] ?? null,
                'vat_number' => $data['vat_number'] ?? null,
                'website' => $data['website'] ?? null,
                'note' => $data['note'] ?? null,
                'is_assignable' => $request->has('is_assignable') ? (bool) $request->input('is_assignable') : true,
                'can_access_finances' => $request->has('can_access_finances') ? (bool) $request->input('can_access_finances') : true,
            ]);

            $vendor->contacts()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'designation' => $data['designation'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'skype' => $data['skype'] ?? null,
                'timezone_id' => $data['timezone_id'] ?? null,
                'gender' => $data['gender'] ?? null,
                'can_login' => $request->boolean('can_login'),
                'is_primary' => true,
            ]);

            if (! empty($data['tags'])) {
                $vendor->tags()->sync($data['tags']);
            }

            return $vendor;
        });

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the vendor detail page.
     */
    public function show(Vendor $vendor): View
    {
        $vendor->load(['legalEntity', 'currency', 'country', 'manager', 'primaryContact.timezone', 'tags', 'payables', 'invoices', 'payments']);

        return view('vendors.show', [
            'vendor' => $vendor,
            'contact' => $vendor->primaryContact,
        ]);
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor): View
    {
        $vendor->load(['primaryContact', 'tags']);

        return view('vendors.edit', $this->formData() + [
            'vendor' => $vendor,
            'contact' => $vendor->primaryContact,
            'selectedTags' => $vendor->tags->pluck('id')->all(),
        ]);
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:individual,freelancer,company,in-house'],
            'status' => ['required', 'in:active,inactive,pending,blocked'],
            'legal_entity_id' => ['nullable', 'exists:legal_entities,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'payment_terms' => ['nullable', 'integer', 'min:0'],
            'region' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'vat_number' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'is_assignable' => ['nullable', 'boolean'],
            'can_access_finances' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:vendor_tags,id'],
            // Primary contact fields
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'skype' => ['nullable', 'string', 'max:100'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'gender' => ['nullable', 'in:male,female'],
            'can_login' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $vendor, $data) {
            $vendor->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'status' => $data['status'],
                'legal_entity_id' => $data['legal_entity_id'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'currency_id' => $data['currency_id'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'payment_terms' => $data['payment_terms'] ?? 0,
                'region' => $data['region'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'tax_number' => $data['tax_number'] ?? null,
                'vat_number' => $data['vat_number'] ?? null,
                'website' => $data['website'] ?? null,
                'note' => $data['note'] ?? null,
                'is_assignable' => $request->has('is_assignable') ? (bool) $request->input('is_assignable') : $vendor->is_assignable,
                'can_access_finances' => $request->has('can_access_finances') ? (bool) $request->input('can_access_finances') : $vendor->can_access_finances,
            ]);

            $contact = $vendor->primaryContact()->first();
            $contactAttributes = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'designation' => $data['designation'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'skype' => $data['skype'] ?? null,
                'timezone_id' => $data['timezone_id'] ?? null,
                'gender' => $data['gender'] ?? null,
                'can_login' => $request->has('can_login') ? $request->boolean('can_login') : ($contact?->can_login ?? false),
            ];

            if ($contact) {
                $contact->update($contactAttributes);
            } else {
                $vendor->contacts()->create($contactAttributes + ['is_primary' => true]);
            }

            $vendor->tags()->sync($data['tags'] ?? []);
        });

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Toggle the vendor's assignable to jobs status.
     */
    public function toggleAssignable(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['is_assignable' => ! $vendor->is_assignable]);

        return back()->with('success', 'Job assignment permission updated.');
    }

    /**
     * Toggle the vendor's finance access permission.
     */
    public function toggleFinances(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['can_access_finances' => ! $vendor->can_access_finances]);

        return back()->with('success', 'Finance access permission updated.');
    }

    /**
     * Toggle whether the primary contact can log in.
     */
    public function toggleCanLogin(Vendor $vendor): RedirectResponse
    {
        $contact = $vendor->primaryContact;
        if ($contact) {
            $contact->update(['can_login' => ! $contact->can_login]);
            return back()->with('success', 'Login access updated.');
        }

        return back()->with('error', 'No primary contact found.');
    }

    /**
     * Send or resend vendor invitation email.
     */
    public function invite(Vendor $vendor): RedirectResponse
    {
        $contact = $vendor->primaryContact;
        $email = $contact?->email;

        if (! $email) {
            return back()->with('error', 'Primary contact does not have an email address.');
        }

        $contact->update(['can_login' => true]);

        return back()->with('success', "Invitation sent to {$email}.");
    }

    /**
     * Export vendors list to CSV / Excel format.
     */
    public function exportExcel(Request $request)
    {
        $search = $request->string('search')->trim();
        $type = $request->string('type')->trim();
        $status = $request->string('status')->trim();

        $vendors = Vendor::query()
            ->with(['country', 'currency', 'primaryContact', 'legalEntity'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('vendor_code', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($type->isNotEmpty(), function ($query) use ($type) {
                if ($type === 'freelancer' || $type === 'individual') {
                    $query->whereIn('type', ['freelancer', 'individual']);
                } else {
                    $query->where('type', $type);
                }
            })
            ->when($status->isNotEmpty(), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="vendors_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($vendors) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Code', 'Name', 'Type', 'Status', 'Legal Entity', 'Country', 'City', 'Primary Contact', 'Email', 'Phone', 'Assignable to jobs', 'Can log in', 'Created at']);
            foreach ($vendors as $v) {
                fputcsv($out, [
                    $v->vendor_code,
                    $v->name,
                    $v->type_label,
                    ucfirst($v->status),
                    $v->legalEntity?->name ?? '',
                    $v->country?->name ?? '',
                    $v->city ?? '',
                    $v->primaryContact?->full_name ?? '',
                    $v->primaryContact?->email ?? '',
                    $v->primaryContact?->phone ?? '',
                    $v->is_assignable ? 'Yes' : 'No',
                    $v->primaryContact?->can_login ? 'Yes' : 'No',
                    $v->created_at?->format('Y-m-d H:i') ?? '',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Shared dropdown options.
     */
    private function formData(): array
    {
        return [
            'legalEntities' => LegalEntity::orderBy('name')->pluck('name', 'id'),
            'countries' => Country::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::orderBy('code')->get()->pluck('code_with_name', 'id')->all() ?: Currency::orderBy('code')->pluck('name', 'id'),
            'timezones' => Timezone::orderBy('label')->pluck('label', 'id'),
            'managers' => User::orderBy('name')->pluck('name', 'id'),
            'tags' => VendorTag::orderBy('name')->get(),
        ];
    }
}
