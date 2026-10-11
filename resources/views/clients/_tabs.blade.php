{{--
    Client view tab navigation, mirroring Protemos.
    Expects: $client, $active (tab key).
--}}
@php
    $tabs = [
        ['key' => 'general', 'label' => 'General info', 'route' => route('clients.show', $client), 'enabled' => true],
        ['key' => 'contacts', 'label' => 'Contact persons', 'route' => route('clients.contacts.index', $client), 'enabled' => true],
        ['key' => 'crm', 'label' => 'CRM', 'route' => '#', 'enabled' => false],
        ['key' => 'prices', 'label' => 'Price list', 'route' => '#', 'enabled' => false],
        ['key' => 'taxes', 'label' => 'Taxes', 'route' => '#', 'enabled' => false],
        ['key' => 'documents', 'label' => 'Documents', 'route' => '#', 'enabled' => false],
        ['key' => 'pools', 'label' => 'Client pools', 'route' => '#', 'enabled' => false],
    ];
@endphp

<div class="tabs-scroll">
    <ul class="nav nav-tabs">
        @foreach ($tabs as $tab)
            @php($isActive = $tab['key'] === $active)
            <li class="nav-item">
                @if ($tab['enabled'])
                    <a href="{{ $tab['route'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">{{ $tab['label'] }}</a>
                @else
                    <span class="nav-link disabled text-muted">
                        {{ $tab['label'] }}
                        <span class="badge badge-secondary ml-1">Soon</span>
                    </span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
