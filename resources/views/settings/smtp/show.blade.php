<x-app-layout>
    <x-slot name="header">
        <x-page-header title="SMTP server" subtitle="Custom mail server used to send system emails.">
            <x-slot name="actions">
                <a href="{{ route('smtp.edit') }}" class="btn btn-primary btn-sm">Update</a>
            </x-slot>
        </x-page-header>
    </x-slot>

    @if ($smtp)
        <x-card title="Configuration" no-padding class="detail-rows-wide">
            <x-detail-row label="Host">{{ $smtp->host }}</x-detail-row>
            <x-detail-row label="Port">{{ $smtp->port }}</x-detail-row>
            <x-detail-row label="Encryption">{{ $smtp->encryption_label }}</x-detail-row>
            <x-detail-row label="Authentication">{{ ucfirst($smtp->authentication) }}</x-detail-row>
            <x-detail-row label="Username">{{ $smtp->username }}</x-detail-row>
            <x-detail-row label="Password">••••••••</x-detail-row>
            <x-detail-row label="From email">{{ $smtp->from_email }}</x-detail-row>
        </x-card>
    @else
        <x-card>
            <p class="text-muted small mb-0">No custom SMTP server configured.</p>
        </x-card>
    @endif
</x-app-layout>
