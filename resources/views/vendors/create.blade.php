<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New vendor" subtitle="Register a new linguist or translation vendor company." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendors.store') }}">
            @csrf

            @include('vendors._form', ['isEdit' => false])

            <div class="mt-4 pt-3 border-top d-flex align-items-center" style="gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check mr-1"></i> {{ __('Create vendor') }}
                </button>
                <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </x-card>
</x-app-layout>
