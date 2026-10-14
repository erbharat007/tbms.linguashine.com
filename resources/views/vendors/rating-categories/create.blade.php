<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New rating category" subtitle="Create vendor performance criteria." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-rating-categories.store') }}">
            @csrf
            @include('vendors.rating-categories._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
