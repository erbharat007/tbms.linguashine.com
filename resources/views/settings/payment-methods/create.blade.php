<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('New payment method') }}" subtitle="{{ \App\Models\PaymentMethod::types()[$type] ?? '' }}" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('payment-methods.store') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                @include('settings.payment-methods._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
