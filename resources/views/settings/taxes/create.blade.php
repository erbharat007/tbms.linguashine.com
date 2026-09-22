<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New tax" subtitle="Tax configuration." />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('taxes.store') }}">
                @csrf
                @include('settings.taxes._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
