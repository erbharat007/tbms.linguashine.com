<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmUserDeletion">
    {{ __('Delete Account') }}
</button>

<div class="modal fade" id="confirmUserDeletion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete account?') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Enter your password to confirm.') }}
                    </p>
                    <div class="form-group mb-0">
                        <x-input-label for="password" :value="__('Password')" class="sr-only" />
                        <x-text-input id="password" name="password" type="password" placeholder="{{ __('Password') }}" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    @push('scripts')
        <script>$(function () { $('#confirmUserDeletion').modal('show'); });</script>
    @endpush
@endif
