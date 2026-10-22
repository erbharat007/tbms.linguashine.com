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
                <div class="btn-group mr-1">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-link mr-1"></i> Quick links
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <span class="dropdown-header font-weight-bold text-uppercase">Payables</span>
                        <a class="dropdown-item" href="{{ route('vendor-payables.index', ['vendor_id' => $vendor->id]) }}">
                            <i class="fas fa-file-invoice-dollar mr-1 text-muted"></i> All payables ({{ $vendor->payables->count() }})
                        </a>
                        <a class="dropdown-item" href="{{ route('vendor-payables.index', ['vendor_id' => $vendor->id, 'status' => 'open']) }}">
                            <i class="fas fa-hourglass-half mr-1 text-muted"></i> Payables without invoice
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-header font-weight-bold text-uppercase">Invoices</span>
                        <a class="dropdown-item" href="{{ route('vendor-invoices.index', ['vendor_id' => $vendor->id]) }}">
                            <i class="fas fa-file-invoice mr-1 text-muted"></i> All invoices ({{ $vendor->invoices->count() }})
                        </a>
                        <a class="dropdown-item" href="{{ route('vendor-invoices.index', ['vendor_id' => $vendor->id, 'status' => 'issued']) }}">
                            <i class="fas fa-exclamation-circle mr-1 text-muted"></i> Invoices without linked payment
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-header font-weight-bold text-uppercase">Payments</span>
                        <a class="dropdown-item" href="{{ route('vendor-payments.index', ['vendor_id' => $vendor->id]) }}">
                            <i class="fas fa-money-check-alt mr-1 text-muted"></i> All payments ({{ $vendor->payments->count() }})
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-header font-weight-bold text-uppercase">Profile & Contacts</span>
                        <a class="dropdown-item" href="{{ route('vendor-prices.index', ['vendor_id' => $vendor->id]) }}">
                            <i class="fas fa-tags mr-1 text-muted"></i> Price list
                        </a>
                        <a class="dropdown-item" href="{{ route('vendors.contacts.index', $vendor) }}">
                            <i class="fas fa-address-book mr-1 text-muted"></i> Contact persons
                        </a>
                    </div>
                </div>

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
                    <x-detail-row label="Type">{{ $vendor->type_label }}</x-detail-row>
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
                    <x-detail-row label="Note">{{ $vendor->note ?: '—' }}</x-detail-row>
                    <x-detail-row label="Created at">{{ $vendor->created_at?->format('Y-m-d H:i') }}</x-detail-row>
                </x-card>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h3 class="card-title font-weight-bold mb-0">Primary user</h3>
                        <div class="card-tools m-0">
                            @if ($contact?->email)
                                <form method="POST" action="{{ route('vendors.invite', $vendor) }}" class="d-inline mr-1">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-info" title="Send or resend login invitation">
                                        <i class="fas fa-paper-plane mr-1"></i> Resend invitation
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-xs btn-outline-primary" title="Update primary user">
                                <i class="fas fa-pen mr-1"></i> Update
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($contact)
                            <x-detail-row label="Full name">{{ $contact->full_name }}</x-detail-row>
                            <x-detail-row label="Email">
                                @if ($contact->email)
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </x-detail-row>
                            <x-detail-row label="Can log in">
                                <div class="d-flex align-items-center justify-content-between">
                                    @if ($contact->can_login)
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Yes</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-times mr-1"></i> No</span>
                                    @endif
                                    <form method="POST" action="{{ route('vendors.toggle-can-login', $vendor) }}" class="d-inline ml-2">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-outline-secondary" title="Toggle login access">
                                            Toggle
                                        </button>
                                    </form>
                                </div>
                            </x-detail-row>
                            <x-detail-row label="Designation">{{ $contact->designation ?: '—' }}</x-detail-row>
                            <x-detail-row label="Gender">{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</x-detail-row>
                            <x-detail-row label="Phone">{{ $contact->phone ?: '—' }}</x-detail-row>
                            <x-detail-row label="Skype">{{ $contact->skype ?: '—' }}</x-detail-row>
                            <x-detail-row label="Time zone">{{ $contact->timezone?->label ?: '—' }}</x-detail-row>
                        @else
                            <div class="p-3 text-muted">No primary contact recorded.</div>
                        @endif
                    </div>
                </div>

                <div class="card card-outline card-secondary mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h3 class="card-title font-weight-bold mb-0">Settings</h3>
                        <div class="card-tools m-0">
                            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-xs btn-outline-primary" title="Update settings">
                                <i class="fas fa-pen mr-1"></i> Update
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <x-detail-row label="Legal entity">{{ $vendor->legalEntity?->name ?: '—' }}</x-detail-row>
                        <x-detail-row label="Default currency">
                            {{ $vendor->currency ? $vendor->currency->code . ' - ' . $vendor->currency->name : '—' }}
                        </x-detail-row>
                        <x-detail-row label="Assignable to jobs">
                            <div class="d-flex align-items-center justify-content-between">
                                @if ($vendor->is_assignable)
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Yes</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times mr-1"></i> No</span>
                                @endif
                                <form method="POST" action="{{ route('vendors.toggle-assignable', $vendor) }}" class="d-inline ml-2">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-secondary" title="Toggle job assignment">
                                        Toggle
                                    </button>
                                </form>
                            </div>
                        </x-detail-row>
                        <x-detail-row label="Can access finances">
                            <div class="d-flex align-items-center justify-content-between">
                                @if ($vendor->can_access_finances)
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Yes</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times mr-1"></i> No</span>
                                @endif
                                <form method="POST" action="{{ route('vendors.toggle-finances', $vendor) }}" class="d-inline ml-2">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-secondary" title="Toggle finance access">
                                        Toggle
                                    </button>
                                </form>
                            </div>
                        </x-detail-row>
                        <x-detail-row label="Payment terms">{{ $vendor->payment_terms ? $vendor->payment_terms . ' days' : 'Immediate' }}</x-detail-row>
                        <x-detail-row label="Account manager">{{ $vendor->manager?->name ?: '—' }}</x-detail-row>
                        <x-detail-row label="Open payables">{{ $vendor->payables->where('status', 'open')->count() }}</x-detail-row>
                        <x-detail-row label="Invoices">{{ $vendor->invoices->count() }}</x-detail-row>
                        <x-detail-row label="Payments">{{ $vendor->payments->count() }}</x-detail-row>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
