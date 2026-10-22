<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Account" subtitle="System account information, defaults, and API keys.">
            <x-slot name="actions">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editAccountModal">
                    <i class="fas fa-edit mr-1"></i> Update account
                </button>
            </x-slot>
        </x-page-header>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Account Details -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-building text-primary mr-2"></i> Account details
                    </h3>
                    <button type="button" class="btn btn-outline-primary btn-xs" data-toggle="modal" data-target="#editAccountModal">
                        Update
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 40%;">Name</th>
                                <td><span class="font-weight-bold">{{ $account->name }}</span></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><span class="badge badge-info">{{ $account->type }}</span></td>
                            </tr>
                            <tr>
                                <th>Owner</th>
                                <td>{{ $account->creator?->name ?? auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <th>Created on</th>
                                <td>{{ $account->created_at ? $account->created_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <td>{{ $account->creator?->name ?? auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <th>API key</th>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="apiKeyField" class="form-control font-monospace" value="{{ $account->api_key }}" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyApiKey()" title="Copy API Key">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <form method="POST" action="{{ route('account.regenerate-api-key') }}" class="d-inline" onsubmit="return confirm('Regenerate API key? Existing integrations will need to be updated.');">
                                                @csrf
                                                <button class="btn btn-outline-warning" type="submit" title="Regenerate Key">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-secondary shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-sliders-h text-secondary mr-2"></i> System settings
                    </h3>
                    <div>
                        <button type="button" class="btn btn-outline-info btn-xs mr-1" data-toggle="modal" data-target="#changeCurrencyModal">
                            Change system currency
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-xs" data-toggle="modal" data-target="#editAccountModal">
                            Update
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 50%;">Timezone for new users</th>
                                <td>{{ $account->timezone }}</td>
                            </tr>
                            <tr>
                                <th>Default legal entity</th>
                                <td>{{ $account->legalEntity?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>System currency</th>
                                <td>
                                    <span class="badge badge-success font-weight-bold">
                                        {{ $account->currency?->code ?? 'USD' }} ({{ $account->currency?->symbol ?? '$' }})
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Default CAT log scheme (clients)</th>
                                <td>{{ $account->clientCatLogTemplate?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Default CAT log scheme (vendors)</th>
                                <td>{{ $account->vendorCatLogTemplate?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Default quote PDF template</th>
                                <td>{{ $account->default_quote_pdf_template ?? 'Standard' }}</td>
                            </tr>
                            <tr>
                                <th>Default client invoice PDF template</th>
                                <td>{{ $account->default_client_invoice_pdf_template ?? 'Standard' }}</td>
                            </tr>
                            <tr>
                                <th>Default vendor invoice PDF template</th>
                                <td>{{ $account->default_vendor_invoice_pdf_template ?? 'Standard' }}</td>
                            </tr>
                            <tr>
                                <th>Default job PO PDF template</th>
                                <td>{{ $account->default_job_po_pdf_template ?? 'Standard' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account & Settings Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('account.update') }}" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="editAccountModalLabel">
                        <i class="fas fa-cog mr-2"></i> Update Account & System Settings
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name" class="font-weight-bold">Account Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $account->name) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="timezone" class="font-weight-bold">Timezone <span class="text-danger">*</span></label>
                            <select name="timezone" id="timezone" class="form-control" required>
                                @foreach ($timezones as $tz)
                                    <option value="{{ $tz->name }}" {{ old('timezone', $account->timezone) == $tz->name ? 'selected' : '' }}>
                                        {{ $tz->label ?? $tz->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="legal_entity_id" class="font-weight-bold">Default Legal Entity</label>
                            <select name="legal_entity_id" id="legal_entity_id" class="form-control">
                                <option value="">— Select Entity —</option>
                                @foreach ($legalEntities as $entity)
                                    <option value="{{ $entity->id }}" {{ old('legal_entity_id', $account->legal_entity_id) == $entity->id ? 'selected' : '' }}>
                                        {{ $entity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="currency_id" class="font-weight-bold">System Currency</label>
                            <select name="currency_id" id="currency_id" class="form-control">
                                @foreach ($currencies as $curr)
                                    <option value="{{ $curr->id }}" {{ old('currency_id', $account->currency_id) == $curr->id ? 'selected' : '' }}>
                                        {{ $curr->code }} - {{ $curr->name }} ({{ $curr->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="client_cat_log_template_id" class="font-weight-bold">Default CAT Scheme (Clients)</label>
                            <select name="client_cat_log_template_id" id="client_cat_log_template_id" class="form-control">
                                <option value="">— None —</option>
                                @foreach ($catLogTemplates as $tmpl)
                                    <option value="{{ $tmpl->id }}" {{ old('client_cat_log_template_id', $account->client_cat_log_template_id) == $tmpl->id ? 'selected' : '' }}>
                                        {{ $tmpl->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="vendor_cat_log_template_id" class="font-weight-bold">Default CAT Scheme (Vendors)</label>
                            <select name="vendor_cat_log_template_id" id="vendor_cat_log_template_id" class="form-control">
                                <option value="">— None —</option>
                                @foreach ($catLogTemplates as $tmpl)
                                    <option value="{{ $tmpl->id }}" {{ old('vendor_cat_log_template_id', $account->vendor_cat_log_template_id) == $tmpl->id ? 'selected' : '' }}>
                                        {{ $tmpl->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="default_quote_pdf_template" class="font-weight-bold">Default Quote PDF Template</label>
                            <input type="text" name="default_quote_pdf_template" id="default_quote_pdf_template" class="form-control" value="{{ old('default_quote_pdf_template', $account->default_quote_pdf_template ?? 'Standard') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="default_client_invoice_pdf_template" class="font-weight-bold">Default Client Invoice PDF Template</label>
                            <input type="text" name="default_client_invoice_pdf_template" id="default_client_invoice_pdf_template" class="form-control" value="{{ old('default_client_invoice_pdf_template', $account->default_client_invoice_pdf_template ?? 'Standard') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="default_vendor_invoice_pdf_template" class="font-weight-bold">Default Vendor Invoice PDF Template</label>
                            <input type="text" name="default_vendor_invoice_pdf_template" id="default_vendor_invoice_pdf_template" class="form-control" value="{{ old('default_vendor_invoice_pdf_template', $account->default_vendor_invoice_pdf_template ?? 'Standard') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="default_job_po_pdf_template" class="font-weight-bold">Default Job PO PDF Template</label>
                            <input type="text" name="default_job_po_pdf_template" id="default_job_po_pdf_template" class="form-control" value="{{ old('default_job_po_pdf_template', $account->default_job_po_pdf_template ?? 'Standard') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Change Currency Modal -->
    <div class="modal fade" id="changeCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="changeCurrencyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('account.update') }}" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $account->name }}">
                <input type="hidden" name="timezone" value="{{ $account->timezone }}">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="changeCurrencyModalLabel">
                        <i class="fas fa-money-bill-wave mr-2"></i> Change System Currency
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        The system currency is used as the base reference currency across all project finances, invoices, and reports.
                    </p>
                    <div class="form-group">
                        <label for="modal_currency_id" class="font-weight-bold">Select Currency</label>
                        <select name="currency_id" id="modal_currency_id" class="form-control" required>
                            @foreach ($currencies as $curr)
                                <option value="{{ $curr->id }}" {{ $account->currency_id == $curr->id ? 'selected' : '' }}>
                                    {{ $curr->code }} - {{ $curr->name }} ({{ $curr->symbol }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update System Currency</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function copyApiKey() {
            var copyText = document.getElementById("apiKeyField");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("API key copied to clipboard!");
        }
    </script>
</x-app-layout>
