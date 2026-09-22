<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update SMTP server configuration" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('smtp.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <x-input-label for="host" :value="__('Host')" />
                            <x-text-input id="host" name="host" type="text" class="mt-1.5"
                                          :value="old('host', $smtp?->host)" required autofocus />
                            <x-input-error :messages="$errors->get('host')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="port" :value="__('Port')" />
                            <x-text-input id="port" name="port" type="number" min="1" max="65535" class="mt-1.5"
                                          :value="old('port', $smtp?->port)" required />
                            <x-input-error :messages="$errors->get('port')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="encryption" :value="__('Encryption')" />
                            <x-select-input id="encryption" name="encryption" class="mt-1.5">
                                <option value="none" @selected(old('encryption', $smtp?->encryption) === 'none')>None</option>
                                <option value="starttls" @selected(old('encryption', $smtp?->encryption) === 'starttls')>STARTTLS</option>
                                <option value="tls" @selected(old('encryption', $smtp?->encryption) === 'tls')>TLS/SSL</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('encryption')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="authentication" :value="__('Authentication')" />
                            <x-select-input id="authentication" name="authentication" class="mt-1.5">
                                @foreach (['auto' => 'Auto', 'plain' => 'PLAIN', 'login' => 'LOGIN', 'ntlm' => 'NTLM', 'xoauth2' => 'XOAUTH2'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('authentication', $smtp?->authentication ?? 'auto') === $value)>{{ $label }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('authentication')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" name="username" type="text" class="mt-1.5"
                                          :value="old('username', $smtp?->username)" required autocomplete="off" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1.5"
                                          :required="! $smtp" autocomplete="new-password" />
                            @if ($smtp)
                                <p class="mt-1 text-xs text-gray-400">Leave blank to keep the current password.</p>
                            @endif
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="from_email" :value="__('From email')" />
                            <x-text-input id="from_email" name="from_email" type="email" class="mt-1.5"
                                          :value="old('from_email', $smtp?->from_email)" required />
                            <x-input-error :messages="$errors->get('from_email')" class="mt-2" />
                        </div>
                    </div>

                    <p class="text-xs text-gray-400">Fields marked as required are mandatory.</p>

                    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                        <a href="{{ route('smtp.show') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
