<div class="row">
    <div class="col-md-6 form-group">
        <label for="name" class="font-weight-bold">Scheme Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $scheme->name) }}" placeholder="e.g. 100% conversion rate or Trados Standard" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="card card-outline card-secondary mb-4">
    <div class="card-header">
        <h4 class="card-title font-weight-bold text-sm">Default settings</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_default_client_account" name="is_default_client_account" value="1"
                           {{ old('is_default_client_account', $scheme->is_default_client_account) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default_client_account">Default for clients in account settings</label>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_default_client_profile" name="is_default_client_profile" value="1"
                           {{ old('is_default_client_profile', $scheme->is_default_client_profile) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default_client_profile">Default in client profiles</label>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_default_vendor_account" name="is_default_vendor_account" value="1"
                           {{ old('is_default_vendor_account', $scheme->is_default_vendor_account) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default_vendor_account">Default for vendors in account settings</label>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_default_vendor_profile" name="is_default_vendor_profile" value="1"
                           {{ old('is_default_vendor_profile', $scheme->is_default_vendor_profile) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default_vendor_profile">Default in vendor profiles</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary mb-4">
    <div class="card-header">
        <h4 class="card-title font-weight-bold text-sm">Analysis match rates (%)</h4>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width: 60%;">Match Band</th>
                    <th>Conversion Rate (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-weight-bold align-middle">Repetitions</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_repetitions" class="form-control"
                                   value="{{ old('rate_repetitions', $scheme->rate_repetitions ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">100% Match</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_100" class="form-control"
                                   value="{{ old('rate_100', $scheme->rate_100 ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">95% – 99%</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_95_99" class="form-control"
                                   value="{{ old('rate_95_99', $scheme->rate_95_99 ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">85% – 94%</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_85_94" class="form-control"
                                   value="{{ old('rate_85_94', $scheme->rate_85_94 ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">75% – 84%</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_75_84" class="form-control"
                                   value="{{ old('rate_75_84', $scheme->rate_75_84 ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">50% – 74%</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_50_74" class="form-control"
                                   value="{{ old('rate_50_74', $scheme->rate_50_74 ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold align-middle">No match (0% – 49%)</td>
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" min="0" max="1000" name="rate_no_match" class="form-control"
                                   value="{{ old('rate_no_match', $scheme->rate_no_match ?? 100) }}" required>
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
