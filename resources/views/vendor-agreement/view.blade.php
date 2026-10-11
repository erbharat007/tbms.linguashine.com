<x-public-layout>
    <div class="text-center mb-4">
        <h1 class="h4 mb-1">Vendor agreement</h1>
        <p class="text-muted small mb-0">{{ config('app.name') }}</p>
    </div>

    <div class="border rounded bg-light p-3 small" style="white-space: pre-line; line-height: 1.6;">{{ $settings->agreement_text }}</div>

    @if ($settings->agreement_updated_at)
        <p class="text-center text-muted small mt-3 mb-0">
            Last updated {{ $settings->agreement_updated_at->format('Y-m-d H:i') }}
        </p>
    @endif
</x-public-layout>
