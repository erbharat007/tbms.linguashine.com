<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit default tax" subtitle="Default tax applied to new clients or vendors." />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('default-taxes.update', $defaultTax) }}">
                @csrf
                @method('PUT')
                @include('settings.default-taxes._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
