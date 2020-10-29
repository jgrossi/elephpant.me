@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ __('Reset Password') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ $email ?? old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            />

                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="new-password"
                            />

                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="actions">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
