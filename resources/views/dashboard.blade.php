<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" :subtitle="'Welcome back, ' . Auth::user()->name . '.'" />
    </x-slot>

    @php($stats = [
        ['label' => 'Active Clients', 'value' => $clientCount, 'note' => 'Clients module', 'bg' => 'bg-info', 'icon' => 'fas fa-user-tie'],
        ['label' => 'Registered Vendors', 'value' => $vendorCount, 'note' => 'Vendor module', 'bg' => 'bg-success', 'icon' => 'fas fa-truck'],
        ['label' => 'Payables Due', 'value' => number_format($payableTotal, 2) . ' EUR', 'note' => $payableCount . ' active orders', 'bg' => 'bg-warning', 'icon' => 'fas fa-file-invoice-dollar'],
        ['label' => 'Supplier Invoices', 'value' => number_format($invoiceTotal, 2) . ' EUR', 'note' => 'Processed billings', 'bg' => 'bg-danger', 'icon' => 'fas fa-receipt'],
    ])

    <div class="row">
        @foreach ($stats as $stat)
            <div class="col-lg-3 col-6">
                <div class="small-box {{ $stat['bg'] }}">
                    <div class="inner">
                        <h3>{{ $stat['value'] }}</h3>
                        <p>{{ $stat['label'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <span class="small-box-footer">{{ $stat['note'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <x-card title="Getting started">
        <p class="mb-0 text-muted">
            This is the foundation of your Translation Business Management System.
            Use the sidebar to manage clients and configure reference data
            (languages, currencies, services, units), and to set up team members
            and roles under Access Control. Vendors, projects, quotes, and finance
            modules arrive in later milestones.
        </p>
    </x-card>
</x-app-layout>
