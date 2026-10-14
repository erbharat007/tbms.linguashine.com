<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New payable" subtitle="Add vendor payable item." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-payables.store') }}">
            @csrf
            @include('vendors.payables._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
