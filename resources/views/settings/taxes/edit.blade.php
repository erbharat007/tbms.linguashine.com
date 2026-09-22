<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit tax" subtitle="Tax configuration." />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('taxes.update', $tax) }}">
                @csrf
                @method('PUT')
                @include('settings.taxes._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
