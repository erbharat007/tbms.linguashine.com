<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New contact person" subtitle="Add a contact person for a vendor." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-contacts.store') }}">
            @csrf

            @include('vendors.contacts._form')

            <div class="mt-4 pt-3 border-top d-flex align-items-center" style="gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check mr-1"></i> {{ __('Save contact') }}
                </button>
                <a href="{{ route('vendor-contacts.index') }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </x-card>
</x-app-layout>
