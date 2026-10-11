<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Default taxes" subtitle="Default taxes applied to new clients and vendors.">
            <x-slot name="actions">
                <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back to taxes</a>
                <a href="{{ route('default-taxes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add default tax') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    @php($firstKey = array_key_first($objectTypes))

    <ul class="nav nav-tabs" role="tablist">
        @foreach ($objectTypes as $key => $label)
            <li class="nav-item">
                <a class="nav-link {{ $key === $firstKey ? 'active' : '' }}" id="tab-{{ $key }}" data-toggle="tab"
                   href="#panel-{{ $key }}" role="tab">
                    {{ $label }}s
                    <span class="badge badge-light ml-1">{{ optional($grouped->get($key))->count() ?? 0 }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content tab-panel">
        @foreach ($objectTypes as $key => $label)
            @php($rows = $grouped->get($key) ?? collect())
            <div class="tab-pane fade {{ $key === $firstKey ? 'show active' : '' }}" id="panel-{{ $key }}" role="tabpanel">
                <x-card no-padding>
                    <x-table :heads="['Tax', 'Type', 'Default value', 'Currency', 'Sort order inside invoices', 'Actions']">
                        @forelse ($rows as $default)
                            <tr>
                                <td>{{ $default->tax?->name }}</td>
                                <td>{{ $default->tax?->type_label }}</td>
                                <td>{{ rtrim(rtrim(number_format((float) $default->amount, 2), '0'), '.') }}</td>
                                <td>
                                    @if ($default->currency)
                                        {{ $default->currency->code }} - {{ $default->currency->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $default->tax?->sort_order }}</td>
                                <td class="text-right text-nowrap">
                                    <a href="{{ route('default-taxes.edit', $default) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                    <x-delete-form :action="route('default-taxes.destroy', $default)" message="Delete this default tax?" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <p class="font-weight-bold mb-1">No default taxes for {{ strtolower($label) }}s yet</p>
                                    <p class="small mb-0">Add a default tax to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>
        @endforeach
    </div>
</x-app-layout>
