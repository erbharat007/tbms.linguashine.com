<x-app-layout>
    <x-slot name="header">
        <x-page-header title="License details" subtitle="System subscription status, managerial limits, and API access.">
            <x-slot name="actions">
                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#updateLicenseModal">
                    <i class="fas fa-key mr-1"></i> Update license key
                </button>
                <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#requestLicenseModal">
                    <i class="fas fa-paper-plane mr-1"></i> Request license
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
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-certificate text-primary mr-2"></i> Current License Information
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 40%;">License status</th>
                                <td>
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fas fa-check-circle mr-1"></i> {{ $license->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>License type</th>
                                <td><span class="font-weight-bold">{{ $license->type }}</span></td>
                            </tr>
                            <tr>
                                <th>Active managers limit</th>
                                <td>
                                    <span class="badge badge-info">{{ $license->managers_limit }}</span>
                                    <span class="text-muted small ml-2">({{ $activeManagers }} currently active)</span>
                                </td>
                            </tr>
                            <tr>
                                <th>API access</th>
                                <td>
                                    @if ($license->api_access)
                                        <span class="badge badge-success">Enabled</span>
                                    @else
                                        <span class="badge badge-secondary">Disabled</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>License expires on</th>
                                <td>
                                    {{ $license->expires_at ? $license->expires_at->format('Y-m-d') : 'Perpetual' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Remaining time</th>
                                <td>
                                    @if ($remainingDays !== null)
                                        <span class="font-weight-bold text-success">{{ $remainingDays }} days</span>
                                    @else
                                        <span class="font-weight-bold text-success">Unlimited / Perpetual</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($license->license_key)
                                <tr>
                                    <th>License key</th>
                                    <td>
                                        <code class="text-dark bg-light px-2 py-1 rounded">{{ $license->license_key }}</code>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-users text-info mr-2"></i> Manager Seats
                    </h3>
                </div>
                <div class="card-body text-center py-4">
                    <div class="display-4 font-weight-bold text-info mb-2">{{ $activeManagers }}</div>
                    <p class="text-muted mb-3">Active Project Managers</p>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="small text-muted mb-0">Total system users registered: <strong>{{ $totalUsers }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Update License Modal -->
    <div class="modal fade" id="updateLicenseModal" tabindex="-1" role="dialog" aria-labelledby="updateLicenseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('license.update') }}" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="updateLicenseModalLabel">
                        <i class="fas fa-key mr-2"></i> Enter New License Key
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="license_key" class="font-weight-bold">License Key <span class="text-danger">*</span></label>
                        <textarea name="license_key" id="license_key" class="form-control" rows="3" placeholder="Paste your Protemos/TBMS license key here..." required>{{ old('license_key', $license->license_key) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Activate License</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request License Modal -->
    <div class="modal fade" id="requestLicenseModal" tabindex="-1" role="dialog" aria-labelledby="requestLicenseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="requestLicenseModalLabel">
                        <i class="fas fa-paper-plane mr-2"></i> Request License Renewal / Upgrade
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        To request an upgraded license or extend your active enterprise manager seats, please reach out to Linguashine support.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope text-primary mr-2"></i> support@linguashine.com</li>
                        <li class="mb-2"><i class="fas fa-globe text-primary mr-2"></i> tbms.linguashine.com</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
