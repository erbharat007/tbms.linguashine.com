<div class="space-y-6">
    <div>
        <x-input-label for="agreement_text" :value="__('Text')" />
        <textarea id="agreement_text" name="agreement_text" rows="12"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                  required autofocus>{{ old('agreement_text', $settings->agreement_text) }}</textarea>
        <p class="mt-1 text-xs text-gray-400">Vendors must accept this text during registration.</p>
        <x-input-error :messages="$errors->get('agreement_text')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update agreement') : __('Create agreement') }}</x-primary-button>
        <a href="{{ route('vendor-portal.show') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
