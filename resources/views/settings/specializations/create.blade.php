<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New specialization" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('specializations.store') }}">
                @csrf
                @include('settings.specializations._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
