<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Vendor;
use App\Models\VendorInvoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorInvoiceController extends Controller
{
    /**
     * Display a listing of vendor invoices (Vendors > Invoices).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $status = $request->string('status')->trim();
        $vendorId = $request->integer('vendor_id');

        $invoices = VendorInvoice::query()
            ->with(['vendor', 'currency'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
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

        return view('vendors.invoices.index', [
            'invoices' => $invoices,
            'search' => $search,
            'status' => $status,
            'vendorId' => $vendorId,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show create invoice form.
     */
    public function create(Request $request): View
    {
        return view('vendors.invoices.create', [
            'invoice' => new VendorInvoice([
                'vendor_id' => $request->integer('vendor_id') ?: null,
                'invoice_date' => now()->toDateString(),
                'status' => 'received',
            ]),
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
        ]);
    }

    /**
     * Store new invoice.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:vendor_invoices,invoice_number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,received,partially_paid,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $subtotal = (float) $data['subtotal'];
        $tax = (float) ($data['tax'] ?? 0);
        $discount = (float) ($data['discount'] ?? 0);
        $data['total'] = round($subtotal + $tax - $discount, 2);

        VendorInvoice::create($data);

        return redirect()->route('vendor-invoices.index')
            ->with('success', 'Vendor invoice created successfully.');
    }

    /**
     * Show invoice details.
     */
    public function show(VendorInvoice $vendorInvoice): View
    {
        $vendorInvoice->load(['vendor.country', 'currency', 'payables']);

        return view('vendors.invoices.show', [
            'invoice' => $vendorInvoice,
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(VendorInvoice $vendorInvoice): View
    {
        return view('vendors.invoices.edit', [
            'invoice' => $vendorInvoice,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
        ]);
    }

    /**
     * Update invoice.
     */
    public function update(Request $request, VendorInvoice $vendorInvoice): RedirectResponse
    {
        $data = $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:vendor_invoices,invoice_number,' . $vendorInvoice->id],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,received,partially_paid,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $subtotal = (float) $data['subtotal'];
        $tax = (float) ($data['tax'] ?? 0);
        $discount = (float) ($data['discount'] ?? 0);
        $data['total'] = round($subtotal + $tax - $discount, 2);

        $vendorInvoice->update($data);

        return redirect()->route('vendor-invoices.index')
            ->with('success', 'Vendor invoice updated successfully.');
    }

    /**
     * Delete invoice.
     */
    public function destroy(VendorInvoice $vendorInvoice): RedirectResponse
    {
        $vendorInvoice->delete();

        return redirect()->route('vendor-invoices.index')
            ->with('success', 'Vendor invoice deleted successfully.');
    }
}
