<section>
    <div class="mb-4">
        <h2 class="h4 text-gray-800">
            {{ __('Update Password') }}
        </h2>
        <p class="text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password">{{ __('Current Password') }}</label>
            <input type="password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   id="update_password_current_password"
                   name="current_password"
                   autocomplete="current-password">
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password">{{ __('New Password') }}</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="update_password_password"
                   name="password"
                   autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   id="update_password_password_confirmation"
                   name="password_confirmation"
                   autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="ml-3 text-success small">
                    <i class="fas fa-check-circle"></i> {{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</section>
