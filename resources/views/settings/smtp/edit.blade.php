<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update SMTP server configuration" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('smtp.update') }}" style="max-width: 680px;">
            @csrf
            @method('PUT')

            <div class="form-group row">
                <label for="host" class="col-sm-4 col-form-label">Host</label>
                <div class="col-sm-8">
                    <x-text-input id="host" name="host" type="text" :value="old('host', $smtp?->host)" required autofocus />
                    <x-input-error :messages="$errors->get('host')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="port" class="col-sm-4 col-form-label">Port</label>
                <div class="col-sm-8">
                    <x-text-input id="port" name="port" type="number" min="1" max="65535" :value="old('port', $smtp?->port)" required />
                    <x-input-error :messages="$errors->get('port')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="encryption" class="col-sm-4 col-form-label">Encryption</label>
                <div class="col-sm-8">
                    <x-select-input id="encryption" name="encryption">
                        <option value="none" @selected(old('encryption', $smtp?->encryption) === 'none')>None</option>
                        <option value="starttls" @selected(old('encryption', $smtp?->encryption) === 'starttls')>STARTTLS</option>
                        <option value="tls" @selected(old('encryption', $smtp?->encryption) === 'tls')>TLS/SSL</option>
                    </x-select-input>
                    <x-input-error :messages="$errors->get('encryption')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="authentication" class="col-sm-4 col-form-label">Authentication</label>
                <div class="col-sm-8">
                    <x-select-input id="authentication" name="authentication">
                        @foreach (['auto' => 'Auto', 'plain' => 'PLAIN', 'login' => 'LOGIN', 'ntlm' => 'NTLM', 'xoauth2' => 'XOAUTH2'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('authentication', $smtp?->authentication ?? 'auto') === $value)>{{ $label }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('authentication')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <x-text-input id="username" name="username" type="text" :value="old('username', $smtp?->username)" required autocomplete="off" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <x-text-input id="password" name="password" type="password" :required="! $smtp" autocomplete="new-password" />
                    @if ($smtp)
                        <small class="form-text text-muted">Leave blank to keep the current password.</small>
                    @endif
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="from_email" class="col-sm-4 col-form-label">From email</label>
                <div class="col-sm-8">
                    <x-text-input id="from_email" name="from_email" type="email" :value="old('from_email', $smtp?->from_email)" required />
                    <x-input-error :messages="$errors->get('from_email')" class="mt-1" />
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                <a href="{{ route('smtp.show') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
