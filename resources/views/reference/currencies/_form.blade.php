@php($currency = $currency ?? null)

<div style="max-width: 560px;">
    <div class="form-group row">
        <label for="code" class="col-sm-3 col-form-label">Code</label>
        <div class="col-sm-9">
            <x-text-input id="code" name="code" type="text" class="text-uppercase" :value="old('code', $currency?->code)" required autofocus maxlength="3" />
            <small class="form-text text-muted">ISO 4217 code, e.g. USD, EUR, INR.</small>
            <x-input-error :messages="$errors->get('code')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-9">
            <x-text-input id="name" name="name" type="text" :value="old('name', $currency?->name)" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <label for="symbol" class="col-sm-3 col-form-label">Symbol</label>
        <div class="col-sm-9">
            <x-text-input id="symbol" name="symbol" type="text" :value="old('symbol', $currency?->symbol)" />
            <x-input-error :messages="$errors->get('symbol')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <span class="col-sm-3 col-form-label">Enabled</span>
        <div class="col-sm-9">
            <input type="hidden" name="is_enabled" value="0">
            <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled" value="1"
                       @checked(old('is_enabled', $currency?->is_enabled ?? true)) />
                <label class="custom-control-label" for="is_enabled"></label>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Currency') : __('Create Currency') }}</button>
    <a href="{{ route('currencies.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
