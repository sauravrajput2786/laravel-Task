@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
    <div class="auth-wrapper">
        <div class="card auth-card">
            <div class="card__header">
                <h1>Sign in</h1>
                <p class="muted">Enter your work email and password to continue.</p>
            </div>

            <x-alert />

            <form id="login-form" action="{{ route('login.attempt') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        autocomplete="username"
                        required
                        autofocus
                    >
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="current-password"
                        required
                    >
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn--primary btn--block" id="login-submit">
                    <span class="btn-label">Sign in</span>
                    <span class="btn-loader" hidden></span>
                </button>
            </form>

            <!-- <p class="muted small">
                Demo accounts: ibmuser@gmail.com &middot; hcluser@gmail.com &middot; infyuser@gmail.com
                (password: <code>Password@123</code>)
            </p> -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            // Client-side presence/format validation and a disabled-button
            // "loading" state while the form submits. The server remains
            // the source of truth for validation (see LoginRequest);
            // this is purely a UX affordance, not a security control.
            $('#login-form').on('submit', function (e) {
                const email = $('#email').val().trim();
                const password = $('#password').val();
                let valid = true;

                $('.field-error--js').remove();

                if (!/^\S+@\S+\.\S+$/.test(email)) {
                    valid = false;
                    $('#email').after('<span class="field-error field-error--js">Please enter a valid email address.</span>');
                }

                if (password.length === 0) {
                    valid = false;
                    $('#password').after('<span class="field-error field-error--js">Please enter your password.</span>');
                }

                if (!valid) {
                    e.preventDefault();
                    TenantApp.toast('Please fix the highlighted fields.', 'error');

                    return;
                }

                $('#login-submit').prop('disabled', true);
                $('#login-submit .btn-label').hide();
                $('#login-submit .btn-loader').prop('hidden', false);
            });
        });
    </script>
@endpush
