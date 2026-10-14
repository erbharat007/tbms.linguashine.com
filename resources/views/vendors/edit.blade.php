<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit vendor — ' . $vendor->name" subtitle="Update vendor profile and primary contact." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendors.update', $vendor) }}">
            @csrf
            @method('PUT')

            @include('vendors._form', ['isEdit' => true])

            <div class="mt-4 pt-3 border-top d-flex align-items-center" style="gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> {{ __('Save changes') }}
                </button>
                <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </x-card>
</x-app-layout>
