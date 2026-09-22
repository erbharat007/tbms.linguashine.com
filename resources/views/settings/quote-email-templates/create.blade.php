<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New email template" subtitle="Quote email template." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('quote-email-templates.store') }}">
                @csrf
                @include('settings.quote-email-templates._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
