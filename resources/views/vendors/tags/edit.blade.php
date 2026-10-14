<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit tag ' . $tag->name" subtitle="Update vendor tag." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-tags.update', $tag) }}">
            @csrf
            @method('PUT')
            @include('vendors.tags._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
