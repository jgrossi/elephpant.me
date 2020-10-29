@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ __('Profile') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        @include('_status')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-md-2">
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input
                                id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autocomplete="name"
                                autofocus
                            />

                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input
                                id="username"
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                autocomplete="twitter"
                            />

                            @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                            />

                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-md-2">
                        <div class="form-group">
                            <label for="country_code">{{ __('Country') }}</label>
                            <select
                                name="country_code"
                                id="country_code"
                                name="country_code"
                                class="form-control @error('country_code') is-invalid @enderror"
                                value="{{ old('country_code') }}"
                                required
                                autocomplete="country_code"
                            >
                                <option value="">-- Select your country --</option>
                                @foreach($countries as $code => $current)
                                    <option value="{{ $code }}" {{ $code === old('country_code', $user->country_code) ? 'selected' : '' }}>
                                        {{ $current->get('name') }}
                                    </option>
                                @endforeach
                            </select>

                            @error('country_code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="twitter">{{ __('Twitter') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input
                                    id="twitter"
                                    type="text"
                                    class="form-control @error('twitter') is-invalid @enderror"
                                    name="twitter"
                                    value="{{ old('twitter', $user->twitter) }}"
                                    autocomplete="twitter"
                                />

                                @error('twitter')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <hr class="separator">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                autocomplete="new-password"
                            />

                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                autocomplete="new-password"
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="actions">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save profile') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
