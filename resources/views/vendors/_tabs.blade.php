{{--
    Vendor view tab navigation, mirroring Protemos.
    Expects: $vendor, $active (tab key).
--}}
@php
    $tabs = [
        ['key' => 'general', 'label' => 'General info', 'route' => route('vendors.show', $vendor), 'enabled' => true],
        ['key' => 'contacts', 'label' => 'Contact persons', 'route' => route('vendors.contacts.index', $vendor), 'enabled' => true],
        ['key' => 'prices', 'label' => 'Prices', 'route' => route('vendor-prices.index', ['vendor_id' => $vendor->id]), 'enabled' => true],
        ['key' => 'payables', 'label' => 'Payables', 'route' => route('vendor-payables.index', ['vendor_id' => $vendor->id]), 'enabled' => true],
        ['key' => 'invoices', 'label' => 'Invoices', 'route' => route('vendor-invoices.index', ['vendor_id' => $vendor->id]), 'enabled' => true],
        ['key' => 'payments', 'label' => 'Payments', 'route' => route('vendor-payments.index', ['vendor_id' => $vendor->id]), 'enabled' => true],
        ['key' => 'payment-methods', 'label' => 'Payment methods', 'route' => route('payment-methods.index'), 'enabled' => true],
        ['key' => 'services', 'label' => 'Services', 'route' => route('services.index'), 'enabled' => true],
        ['key' => 'language-pairs', 'label' => 'Language pairs', 'route' => route('language-pairs.index'), 'enabled' => true],
    ];
@endphp

<div class="tabs-scroll">
    <ul class="nav nav-tabs">
        @foreach ($tabs as $tab)
            @php($isActive = $tab['key'] === $active)
            <li class="nav-item">
                <a href="{{ $tab['route'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">{{ $tab['label'] }}</a>
            </li>
        @endforeach
    </ul>
</div>
