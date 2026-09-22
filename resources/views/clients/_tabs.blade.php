{{--
    Client view tab navigation, mirroring Protemos.
    Expects: $client, $active (tab key).
    Only "General info" is built; the rest are placeholders for later steps.
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

<div class="border-b border-gray-200">
    <nav class="-mb-px flex flex-wrap gap-x-6 gap-y-2 text-sm">
        @foreach ($tabs as $tab)
            @php($isActive = $tab['key'] === $active)
            @if ($tab['enabled'])
                <a href="{{ $tab['route'] }}"
                   class="border-b-2 px-1 py-3 font-medium transition {{ $isActive ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    {{ $tab['label'] }}
                </a>
            @else
                <span class="flex items-center gap-1.5 border-b-2 border-transparent px-1 py-3 font-medium text-gray-300"
                      title="Coming soon">
                    {{ $tab['label'] }}
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-medium uppercase tracking-wide text-gray-400">Soon</span>
                </span>
            @endif
        @endforeach
    </nav>
</div>
