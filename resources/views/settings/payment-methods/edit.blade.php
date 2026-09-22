<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Edit payment method') }}" subtitle="{{ $paymentMethod->type_label }}" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('payment-methods.update', $paymentMethod) }}">
                @csrf
                @method('PUT')
                @include('settings.payment-methods._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
