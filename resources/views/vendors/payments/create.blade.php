<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Record payment" subtitle="Log outgoing payment to vendor." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-payments.store') }}">
            @csrf
            @include('vendors.payments._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
