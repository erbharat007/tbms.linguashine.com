<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\VendorInvoice;
use App\Models\VendorPayable;

class DashboardController extends Controller
{
    /**
     * Render the executive dashboard with real KPI metrics.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $clientCount = Client::count();
        $vendorCount = Vendor::count();
        $payableCount = VendorPayable::count();
        $payableTotal = VendorPayable::sum('amount');
        $invoiceTotal = VendorInvoice::sum('total');

        return view('dashboard', compact(
            'clientCount',
            'vendorCount',
            'payableCount',
            'payableTotal',
            'invoiceTotal'
        ));
    }
}

