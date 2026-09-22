<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" :subtitle="'Welcome back, ' . Auth::user()->name . '.'" />
    </x-slot>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @php($stats = [
            ['label' => 'Clients', 'value' => '—', 'note' => 'Clients module', 'color' => 'bg-indigo-50 text-indigo-600'],
            ['label' => 'Vendors', 'value' => '—', 'note' => 'Milestone 2', 'color' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Active Projects', 'value' => '—', 'note' => 'Milestone 3', 'color' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Outstanding', 'value' => '—', 'note' => 'Milestone 5', 'color' => 'bg-rose-50 text-rose-600'],
        ])

        @foreach ($stats as $stat)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $stat['color'] }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        <x-card title="Getting started">
            <p class="text-sm leading-relaxed text-gray-600">
                This is the foundation of your Translation Business Management System.
                Use the sidebar to manage clients and configure reference data
                (languages, currencies, services, units), and to set up team members
                and roles under Settings. Vendors, projects, quotes, and finance
                modules arrive in later milestones.
            </p>
        </x-card>
    </div>
</x-app-layout>
