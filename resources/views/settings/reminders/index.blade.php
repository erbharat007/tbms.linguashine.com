<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Reminders" subtitle="Automated email notifications for upcoming deadlines, due dates, and expirations.">
            <x-slot name="actions">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editRemindersModal">
                    <i class="fas fa-edit mr-1"></i> Update
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
        <div class="col-lg-8">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-bell text-primary mr-2"></i> Active Reminder Rules
                    </h3>
                    <button type="button" class="btn btn-outline-primary btn-xs" data-toggle="modal" data-target="#editRemindersModal">
                        Update
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 60%;">Send project deadline reminders</th>
                                <td>
                                    @if ($reminders->project_deadline)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                        <span class="text-muted small ml-2">({{ $reminders->project_deadline_days }} day before)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Send job deadline reminders</th>
                                <td>
                                    @if ($reminders->job_deadline)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                        <span class="text-muted small ml-2">({{ $reminders->job_deadline_days }} day before)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Send client invoice due date reminders</th>
                                <td>
                                    @if ($reminders->client_invoice_due)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                        <span class="text-muted small ml-2">({{ $reminders->client_invoice_days }} days before)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Send vendor invoice due date reminders</th>
                                <td>
                                    @if ($reminders->vendor_invoice_due)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                        <span class="text-muted small ml-2">({{ $reminders->vendor_invoice_days }} days before)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Send quote expiration reminders</th>
                                <td>
                                    @if ($reminders->quote_expiration)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                        <span class="text-muted small ml-2">({{ $reminders->quote_expiration_days }} days before)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-info-circle text-info mr-2"></i> About Reminders
                    </h3>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        System reminders send automatic email notifications to assigned project managers, clients, and vendors based on upcoming milestones.
                    </p>
                    <p class="small text-muted mb-0">
                        Email templates for these reminders can be customized in the <strong>Email Templates</strong> section.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Reminders Modal -->
    <div class="modal fade" id="editRemindersModal" tabindex="-1" role="dialog" aria-labelledby="editRemindersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('reminders.update') }}" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="editRemindersModalLabel">
                        <i class="fas fa-bell mr-2"></i> Configure Reminders
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group border-bottom pb-3">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="project_deadline" name="project_deadline" value="1"
                                   {{ old('project_deadline', $reminders->project_deadline) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="project_deadline">
                                Send project deadline reminders
                            </label>
                        </div>
                        <div class="row align-items-center pl-4">
                            <div class="col-auto"><label class="small text-muted mb-0">Send reminder</label></div>
                            <div class="col-auto">
                                <input type="number" name="project_deadline_days" class="form-control form-control-sm" style="width: 80px;"
                                       value="{{ old('project_deadline_days', $reminders->project_deadline_days) }}" min="1" max="30">
                            </div>
                            <div class="col-auto"><label class="small text-muted mb-0">day(s) before deadline</label></div>
                        </div>
                    </div>

                    <div class="form-group border-bottom pb-3">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="job_deadline" name="job_deadline" value="1"
                                   {{ old('job_deadline', $reminders->job_deadline) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="job_deadline">
                                Send job deadline reminders
                            </label>
                        </div>
                        <div class="row align-items-center pl-4">
                            <div class="col-auto"><label class="small text-muted mb-0">Send reminder</label></div>
                            <div class="col-auto">
                                <input type="number" name="job_deadline_days" class="form-control form-control-sm" style="width: 80px;"
                                       value="{{ old('job_deadline_days', $reminders->job_deadline_days) }}" min="1" max="30">
                            </div>
                            <div class="col-auto"><label class="small text-muted mb-0">day(s) before deadline</label></div>
                        </div>
                    </div>

                    <div class="form-group border-bottom pb-3">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="client_invoice_due" name="client_invoice_due" value="1"
                                   {{ old('client_invoice_due', $reminders->client_invoice_due) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="client_invoice_due">
                                Send client invoice due date reminders
                            </label>
                        </div>
                        <div class="row align-items-center pl-4">
                            <div class="col-auto"><label class="small text-muted mb-0">Send reminder</label></div>
                            <div class="col-auto">
                                <input type="number" name="client_invoice_days" class="form-control form-control-sm" style="width: 80px;"
                                       value="{{ old('client_invoice_days', $reminders->client_invoice_days) }}" min="1" max="60">
                            </div>
                            <div class="col-auto"><label class="small text-muted mb-0">day(s) before due date</label></div>
                        </div>
                    </div>

                    <div class="form-group border-bottom pb-3">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="vendor_invoice_due" name="vendor_invoice_due" value="1"
                                   {{ old('vendor_invoice_due', $reminders->vendor_invoice_due) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="vendor_invoice_due">
                                Send vendor invoice due date reminders
                            </label>
                        </div>
                        <div class="row align-items-center pl-4">
                            <div class="col-auto"><label class="small text-muted mb-0">Send reminder</label></div>
                            <div class="col-auto">
                                <input type="number" name="vendor_invoice_days" class="form-control form-control-sm" style="width: 80px;"
                                       value="{{ old('vendor_invoice_days', $reminders->vendor_invoice_days) }}" min="1" max="60">
                            </div>
                            <div class="col-auto"><label class="small text-muted mb-0">day(s) before due date</label></div>
                        </div>
                    </div>

                    <div class="form-group pb-2">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="quote_expiration" name="quote_expiration" value="1"
                                   {{ old('quote_expiration', $reminders->quote_expiration) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="quote_expiration">
                                Send quote expiration reminders
                            </label>
                        </div>
                        <div class="row align-items-center pl-4">
                            <div class="col-auto"><label class="small text-muted mb-0">Send reminder</label></div>
                            <div class="col-auto">
                                <input type="number" name="quote_expiration_days" class="form-control form-control-sm" style="width: 80px;"
                                       value="{{ old('quote_expiration_days', $reminders->quote_expiration_days) }}" min="1" max="30">
                            </div>
                            <div class="col-auto"><label class="small text-muted mb-0">day(s) before expiration</label></div>
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
</x-app-layout>
