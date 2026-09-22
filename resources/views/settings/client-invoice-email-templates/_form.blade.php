@php($template = $template ?? null)

<div class="space-y-6">
    {{-- Available variables help panel --}}
    <div class="rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3">
        <p class="text-sm font-medium text-indigo-800">The following variables can be used in the email subject and body:</p>
        <ul class="mt-2 space-y-1 text-xs text-indigo-700">
            @foreach (\App\Models\ClientInvoiceEmailTemplate::variables() as $token => $description)
                <li><code class="rounded bg-white/70 px-1 py-0.5 font-semibold">{{ $token }}</code> — {{ $description }}</li>
            @endforeach
        </ul>
    </div>

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $template?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="subject" :value="__('Email subject')" />
        <x-text-input id="subject" name="subject" type="text" class="mt-1.5"
                      :value="old('subject', $template?->subject)" required />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="body" :value="__('Email body')" />
        <textarea id="body" name="body" rows="10"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                  required>{{ old('body', $template?->body) }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update template') : __('Create template') }}</x-primary-button>
        <a href="{{ route('client-invoice-email-templates.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
