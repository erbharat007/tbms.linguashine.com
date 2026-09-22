<x-guest-layout>
    <div class="text-center">
        <h1 class="text-lg font-semibold text-gray-900">Vendor agreement</h1>
        <p class="mt-1 text-sm text-gray-500">{{ config('app.name') }}</p>
    </div>

    <div class="mt-6 whitespace-pre-line rounded-md border border-gray-200 bg-gray-50 px-4 py-4 text-sm leading-relaxed text-gray-700">{{ $settings->agreement_text }}</div>

    @if ($settings->agreement_updated_at)
        <p class="mt-4 text-center text-xs text-gray-400">
            Last updated {{ $settings->agreement_updated_at->format('Y-m-d H:i') }}
        </p>
    @endif
</x-guest-layout>
