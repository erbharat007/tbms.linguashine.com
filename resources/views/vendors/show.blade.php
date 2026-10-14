<x-app-layout>
    <x-slot name="header">
        <div class="row mb-2 align-items-center">
            <div class="col-sm">
                <ol class="breadcrumb bg-transparent p-0 mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->name }}</li>
                </ol>
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <h1 class="m-0 h3 font-weight-bold">{{ $vendor->name }}</h1>
                    @if ($vendor->vendor_code)
                        <span class="badge badge-light border text-monospace">{{ $vendor->vendor_code }}</span>
                    @endif
                    @php
                        $badgeColor = match($vendor->status) {
                            'active' => 'success',
                            'pending' => 'warning',
                            'blocked' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($vendor->status) }}</span>
                </div>
            </div>
            <div class="col-sm-auto mt-2 mt-sm-0 text-sm-right">
                <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen mr-1"></i> Update
                </a>
                <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" class="d-inline"
                      onsubmit="return confirm('Delete this vendor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @include('vendors._tabs', ['active' => 'general'])

    <div class="tab-panel">
        <div class="row">
            <div class="col-lg-8">
                <x-card title="Vendor details" no-padding>
                    <x-detail-row label="Code">{{ $vendor->vendor_code ?: '—' }}</x-detail-row>
                    <x-detail-row label="Name">{{ $vendor->name }}</x-detail-row>
                    <x-detail-row label="Type">{{ ucfirst($vendor->type) }}</x-detail-row>
                    <x-detail-row label="Status">
                        <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($vendor->status) }}</span>
                    </x-detail-row>
                    <x-detail-row label="Tags">
                        <div class="d-flex flex-wrap" style="gap: 5px;">
                            @forelse ($vendor->tags as $tag)
                                <span class="badge badge-{{ $tag->color }}">{{ $tag->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </div>
                    </x-detail-row>
                    <x-detail-row label="Tax number">{{ $vendor->tax_number ?: '—' }}</x-detail-row>
                    <x-detail-row label="VAT number">{{ $vendor->vat_number ?: '—' }}</x-detail-row>
                    <x-detail-row label="Country">{{ $vendor->country?->name ?: '—' }}</x-detail-row>
                    <x-detail-row label="Region / State">{{ $vendor->region ?: '—' }}</x-detail-row>
                    <x-detail-row label="City">{{ $vendor->city ?: '—' }}</x-detail-row>
                    <x-detail-row label="Postal / Zip code">{{ $vendor->postal_code ?: '—' }}</x-detail-row>
                    <x-detail-row label="Address">{{ $vendor->address ?: '—' }}</x-detail-row>
                    <x-detail-row label="Website">
                        @if ($vendor->website)
                            <a href="{{ $vendor->website }}" target="_blank" rel="noopener">{{ $vendor->website }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </x-detail-row>
                    <x-detail-row label="Payment terms">{{ $vendor->payment_terms ? $vendor->payment_terms . ' days' : 'Immediate' }}</x-detail-row>
                    <x-detail-row label="Account manager">{{ $vendor->manager?->name ?: '—' }}</x-detail-row>
                    <x-detail-row label="Note">{{ $vendor->note ?: '—' }}</x-detail-row>
                    <x-detail-row label="Created at">{{ $vendor->created_at?->format('Y-m-d H:i') }}</x-detail-row>
                </x-card>
            </div>

            <div class="col-lg-4">
                <x-card title="Primary contact" no-padding>
                    @if ($contact)
                        <x-detail-row label="Full name">{{ $contact->full_name }}</x-detail-row>
                        <x-detail-row label="Designation">{{ $contact->designation ?: '—' }}</x-detail-row>
                        <x-detail-row label="Gender">{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</x-detail-row>
                        <x-detail-row label="Email">
                            @if ($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </x-detail-row>
                        <x-detail-row label="Phone">{{ $contact->phone ?: '—' }}</x-detail-row>
                        <x-detail-row label="Skype">{{ $contact->skype ?: '—' }}</x-detail-row>
                        <x-detail-row label="Time zone">{{ $contact->timezone?->label ?: '—' }}</x-detail-row>
                    @else
                        <div class="p-3 text-muted">No primary contact recorded.</div>
                    @endif
                </x-card>

                <x-card title="Settings & Billing" no-padding>
                    <x-detail-row label="Legal entity">{{ $vendor->legalEntity?->name ?: '—' }}</x-detail-row>
                    <x-detail-row label="Default currency">
                        {{ $vendor->currency ? $vendor->currency->code . ' - ' . $vendor->currency->name : '—' }}
                    </x-detail-row>
                    <x-detail-row label="Open payables">{{ $vendor->payables->where('status', 'open')->count() }}</x-detail-row>
                    <x-detail-row label="Invoices">{{ $vendor->invoices->count() }}</x-detail-row>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
