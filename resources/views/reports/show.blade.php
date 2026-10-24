<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="$title" :subtitle="$subtitle">
            <x-slot name="actions">
                <button type="button" class="btn btn-outline-secondary btn-sm mr-1" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
                <button type="button" class="btn btn-outline-success btn-sm mr-1" onclick="alert('Exporting data to Excel...')">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="alert('Exporting data to CSV...')">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </button>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- Filter Bar --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="form-row align-items-center">
                <div class="col-auto">
                    <label class="sr-only" for="period">Period</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <select name="period" id="period" class="form-control form-control-sm">
                            <option value="this_month" @selected($selectedPeriod === 'this_month')>This Month</option>
                            <option value="last_month" @selected($selectedPeriod === 'last_month')>Last Month</option>
                            <option value="this_quarter" @selected($selectedPeriod === 'this_quarter')>This Quarter</option>
                            <option value="this_year" @selected($selectedPeriod === 'this_year')>This Year (2026)</option>
                            <option value="all_time" @selected($selectedPeriod === 'all_time')>All Time</option>
                        </select>
                    </div>
                </div>

                <div class="col-auto">
                    <label class="sr-only" for="currency">Currency</label>
                    <select name="currency" id="currency" class="form-control form-control-sm">
                        @foreach ($currencies as $curr)
                            <option value="{{ $curr->code }}" @selected($selectedCurrency === $curr->code)>{{ $curr->code }} ({{ $curr->symbol ?: $curr->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <label class="sr-only" for="legal_entity_id">Legal Entity</label>
                    <select name="legal_entity_id" id="legal_entity_id" class="form-control form-control-sm">
                        <option value="">All Legal Entities</option>
                        @foreach ($legalEntities as $le)
                            <option value="{{ $le->id }}" @selected($selectedEntity == $le->id)>{{ $le->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Apply
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-muted">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- KPI Cards --}}
    @if (!empty($kpis))
        <div class="row">
            @foreach ($kpis as $kpi)
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $kpi['bg'] }}">
                        <div class="inner">
                            <h3 style="font-size: 1.8rem; font-weight: 700;">{{ $kpi['value'] }}</h3>
                            <p class="mb-1 font-weight-bold">{{ $kpi['label'] }}</p>
                        </div>
                        <div class="icon">
                            <i class="{{ $kpi['icon'] }}"></i>
                        </div>
                        <span class="small-box-footer py-1">{{ $kpi['note'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Report Data Table Card --}}
    <x-card no-padding>
        <div class="card-header border-bottom py-2 d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark">
                <i class="fas fa-table mr-1 text-muted"></i> Report Ledger ({{ count($rows) }} records)
            </span>
            <span class="badge badge-light border">Filtered: {{ ucwords(str_replace('_', ' ', $selectedPeriod)) }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        @foreach ($heads as $head)
                            <th class="{{ in_array($head, ['Receivables', 'Payables', 'Gross Profit', 'Margin %', 'Quoted Value', 'Total Value', 'Amount', 'Total Receivables', 'Total Payables', 'Profit', 'Delivered Words', 'Words Count', 'Volume']) ? 'text-right' : '' }}">
                                {{ $head }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            @foreach ($row as $key => $val)
                                <td class="{{ in_array($key, ['Receivables', 'Payables', 'Profit', 'Margin', 'Value', 'Amount', 'Total', 'Paid', 'Net', 'Subtotal', 'Tax', 'Rate', 'AvgRev']) ? 'text-right font-weight-bold' : '' }}">
                                    @if ($key === 'Status')
                                        @if (in_array($val, ['Accepted', 'Completed', 'Paid', 'Reconciled']))
                                            <span class="badge badge-success px-2 py-1">{{ $val }}</span>
                                        @elseif (in_array($val, ['In Progress', 'In Review', 'Under Review']))
                                            <span class="badge badge-info px-2 py-1">{{ $val }}</span>
                                        @elseif (in_array($val, ['Partially Paid', 'Pending', 'Pending Review', 'Ready to Send']))
                                            <span class="badge badge-warning px-2 py-1">{{ $val }}</span>
                                        @elseif (in_array($val, ['Rejected', 'Cancelled', 'Overdue']))
                                            <span class="badge badge-danger px-2 py-1">{{ $val }}</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">{{ $val }}</span>
                                        @endif
                                    @elseif ($key === 'Priority')
                                        @if ($val === 'High')
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation mr-1"></i> High</span>
                                        @elseif ($val === 'Medium')
                                            <span class="badge badge-warning px-2 py-1">Medium</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">Low</span>
                                        @endif
                                    @elseif ($key === 'Margin' && str_ends_with($val, '%'))
                                        <span class="text-success">{{ $val }}</span>
                                    @else
                                        {{ $val }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($heads) }}" class="text-center py-4 text-muted">
                                No records found for the selected filter parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
