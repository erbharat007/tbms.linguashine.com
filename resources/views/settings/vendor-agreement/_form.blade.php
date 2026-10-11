<div class="form-group">
    <x-input-label for="agreement_text" :value="__('Text')" />
    <textarea id="agreement_text" name="agreement_text" rows="12" class="form-control" required autofocus>{{ old('agreement_text', $settings->agreement_text) }}</textarea>
    <small class="form-text text-muted">Vendors must accept this text during registration.</small>
    <x-input-error :messages="$errors->get('agreement_text')" class="mt-1" />
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update agreement') : __('Create agreement') }}</button>
    <a href="{{ route('vendor-portal.show') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
