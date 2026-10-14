<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit category ' . $category->name" subtitle="Update vendor performance criteria." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-rating-categories.update', $category) }}">
            @csrf
            @method('PUT')
            @include('vendors.rating-categories._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
