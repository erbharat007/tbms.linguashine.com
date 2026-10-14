<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New tag" subtitle="Create vendor classification tag." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-tags.store') }}">
            @csrf
            @include('vendors.tags._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
