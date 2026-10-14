<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorPaymentController extends Controller
{
    /**
     * Display a listing of vendor payments (Vendors > Payments).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $vendorId = $request->integer('vendor_id');

        $payments = VendorPayment::query()
            ->with(['vendor', 'currency', 'paymentMethod'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payment_number', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%")
                        ->orWhereHas('vendor', function ($vq) use ($search) {
                            $vq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($vendorId > 0, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('vendors.payments.index', [
            'payments' => $payments,
            'search' => $search,
            'vendorId' => $vendorId,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Show create payment form.
     */
    public function create(Request $request): View
    {
        return view('vendors.payments.create', [
            'payment' => new VendorPayment([
                'vendor_id' => $request->integer('vendor_id') ?: null,
                'payment_date' => now()->toDateString(),
                'status' => 'paid',
            ]),
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
            'paymentMethods' => PaymentMethod::where('is_enabled', true)->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Store new payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'reference_number' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:paid,cancelled'],
        ]);

        $data['payment_number'] = 'VPAY-' . date('Y') . '-' . str_pad((VendorPayment::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        VendorPayment::create($data);

        return redirect()->route('vendor-payments.index')
            ->with('success', 'Vendor payment recorded successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorPayment $vendorPayment): View
    {
        return view('vendors.payments.edit', [
            'payment' => $vendorPayment,
            'vendors' => Vendor::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_enabled', true)->orderBy('code')->pluck('code', 'id'),
            'paymentMethods' => PaymentMethod::where('is_enabled', true)->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Update payment.
     */
    public function update(Request $request, VendorPayment $vendorPayment): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'reference_number' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:paid,cancelled'],
        ]);

        $vendorPayment->update($data);

        return redirect()->route('vendor-payments.index')
            ->with('success', 'Vendor payment updated successfully.');
    }

    /**
     * Delete payment.
     */
    public function destroy(VendorPayment $vendorPayment): RedirectResponse
    {
        $vendorPayment->delete();

        return redirect()->route('vendor-payments.index')
            ->with('success', 'Vendor payment deleted successfully.');
    }
}
