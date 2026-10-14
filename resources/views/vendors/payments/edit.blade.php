<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit payment ' . $payment->payment_number" subtitle="Update outgoing payment record." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-payments.update', $payment) }}">
            @csrf
            @method('PUT')
            @include('vendors.payments._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
