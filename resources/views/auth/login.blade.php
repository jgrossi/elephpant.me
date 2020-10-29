@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ __('Login') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                                value="{{ old('email') }}"
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
                                autocomplete="current-password"
                            />

                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 offset-md-2">
                        <div class="form-group checkbox-container">
                            <label class="container">{{ __('Remember Me') }}
                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                />
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="actions">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
