@php($template = $template ?? null)

{{-- Available variables help panel --}}
<div class="alert alert-info" role="alert">
    <p class="font-weight-bold mb-2">The following variables can be used in the email subject and body:</p>
    <ul class="mb-0 pl-3 small">
        @foreach (\App\Models\ClientInvoiceEmailTemplate::variables() as $token => $description)
            <li><code>{{ $token }}</code> — {{ $description }}</li>
        @endforeach
    </ul>
</div>

<div class="form-group">
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" :value="old('name', $template?->name)" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-1" />
</div>

<div class="form-group">
    <x-input-label for="subject" :value="__('Email subject')" />
    <x-text-input id="subject" name="subject" type="text" :value="old('subject', $template?->subject)" required />
    <x-input-error :messages="$errors->get('subject')" class="mt-1" />
</div>

<div class="form-group">
    <x-input-label for="body" :value="__('Email body')" />
    <textarea id="body" name="body" rows="10" class="form-control" required>{{ old('body', $template?->body) }}</textarea>
    <x-input-error :messages="$errors->get('body')" class="mt-1" />
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update template') : __('Create template') }}</button>
    <a href="{{ route('client-invoice-email-templates.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
