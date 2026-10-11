<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Profile" subtitle="Manage your account information and security." />
    </x-slot>

    <x-card title="Profile Information" subtitle="Update your account's profile information and email address." class="mb-4">
        @include('profile.partials.update-profile-information-form')
    </x-card>

    <x-card title="Update Password" subtitle="Ensure your account is using a long, random password to stay secure." class="mb-4">
        @include('profile.partials.update-password-form')
    </x-card>

    <x-card title="Delete Account" subtitle="Permanently delete your account and all of its data.">
        @include('profile.partials.delete-user-form')
    </x-card>
</x-app-layout>
