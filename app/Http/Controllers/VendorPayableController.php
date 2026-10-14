<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorPayable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorPayableController extends Controller
{
    /**
     * Display a listing of vendor payables (Vendors > Payables).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $status = $request->string('status')->trim();
        $vendorId = $request->integer('vendor_id');

        $payables = VendorPayable::query()
            ->with(['vendor', 'currency', 'unit'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payable_number', 'like', "%{$search}%")
                        ->orWhere('job_code', 'like', "%{$search}%")
                        ->orWhere('project_name', 'like', "%{$search}%")
                        ->orWhereHas('vendor', function ($vq) use ($search) {
                            $vq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status->isNotEmpty(), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($vendorId > 0, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('vendors.payables.index', [
            'payables' => $payables,
            'search' => $search,
            'status' => $status,
            'vendorId' => $vendorId,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show create payable form.
     */
    public function create(Request $request): View
    {
        return view('vendors.payables.create', [
            'payable' => new VendorPayable([
                'vendor_id' => $request->integer('vendor_id') ?: null,
                'quantity' => 1,
                'status' => 'open',
            ]),
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'units' => Unit::where('is_enabled', true)->orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
        ]);
    }

    /**
     * Store new payable.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'job_code' => ['nullable', 'string', 'max:50'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'rate' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:open,invoiced,paid,cancelled'],
        ]);

        $data['payable_number'] = 'PAY-' . date('Y') . '-' . str_pad((VendorPayable::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        $data['amount'] = round($data['quantity'] * $data['rate'], 2);

        VendorPayable::create($data);

        return redirect()->route('vendor-payables.index')
            ->with('success', 'Payable created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorPayable $vendorPayable): View
    {
        return view('vendors.payables.edit', [
            'payable' => $vendorPayable,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'units' => Unit::where('is_enabled', true)->orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
        ]);
    }

    /**
     * Update payable.
     */
    public function update(Request $request, VendorPayable $vendorPayable): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'job_code' => ['nullable', 'string', 'max:50'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'rate' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:open,invoiced,paid,cancelled'],
        ]);

        $data['amount'] = round($data['quantity'] * $data['rate'], 2);

        $vendorPayable->update($data);

        return redirect()->route('vendor-payables.index')
            ->with('success', 'Payable updated successfully.');
    }

    /**
     * Delete payable.
     */
    public function destroy(VendorPayable $vendorPayable): RedirectResponse
    {
        $vendorPayable->delete();

        return redirect()->route('vendor-payables.index')
            ->with('success', 'Payable deleted successfully.');
    }
}
