<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $paymentMethods = PaymentMethod::orderBy('name')->paginate(20);

        return view('settings.payment-methods.index', [
            'paymentMethods' => $paymentMethods,
            'types' => PaymentMethod::types(),
        ]);
    }

    /**
     * Show the create form for a chosen type.
     */
    public function create(Request $request): View
    {
        $type = $request->string('type')->toString();

        abort_unless(array_key_exists($type, PaymentMethod::types()), 404);

        return view('settings.payment-methods.create', [
            'type' => $type,
            'paymentMethod' => new PaymentMethod(['type' => $type]),
        ]);
    }

    public function store(StorePaymentMethodRequest $request): RedirectResponse
    {
        PaymentMethod::create($request->paymentMethodData());

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('settings.payment-methods.edit', [
            'type' => $paymentMethod->type,
            'paymentMethod' => $paymentMethod,
        ]);
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->update($request->paymentMethodData());

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function toggle(PaymentMethod $paymentMethod): RedirectResponse
    {
        if ($paymentMethod->is_default && $paymentMethod->is_enabled) {
            return redirect()->route('payment-methods.index')
                ->with('error', 'The default payment method cannot be disabled.');
        }

        $paymentMethod->update(['is_enabled' => ! $paymentMethod->is_enabled]);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method ' . ($paymentMethod->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        if ($paymentMethod->is_default) {
            return redirect()->route('payment-methods.index')
                ->with('error', 'The default payment method cannot be deleted.');
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}
