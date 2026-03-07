@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 pt-8">
    <flux:card class="space-y-6">
        <flux:heading size="lg">{{ __('Register') }}</flux:heading>
        <div>
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <flux:field>
                    <flux:label>{{ __('Name') }}</flux:label>
                    <flux:input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('E-Mail Address') }}</flux:label>
                    <flux:input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Country') }}</flux:label>
                    <flux:select name="country_code" id="country_code" required>
                        <option value="">-- {{ __('Select your country') }} --</option>
                        @foreach($countries as $code => $current)
                            <option value="{{ $code }}" {{ $code === old('country_code') ? 'selected' : '' }}>
                                {{ is_array($current) ? ($current['name'] ?? '') : $current->get('name') }}
                            </option>
                        @endforeach
                    </flux:select>
                    <flux:error name="country_code" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Twitter') }}</flux:label>
                    <flux:input type="text" name="twitter" id="twitter" value="{{ old('twitter') }}" placeholder="@username" autocomplete="twitter" />
                    <flux:error name="twitter" />
                </flux:field>

                <flux:separator />

                <flux:field>
                    <flux:label>{{ __('Password') }}</flux:label>
                    <flux:input type="password" name="password" id="password" required autocomplete="new-password" />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Confirm Password') }}</flux:label>
                    <flux:input type="password" name="password_confirmation" id="password-confirm" required autocomplete="new-password" />
                </flux:field>

                <flux:button type="submit" variant="primary">{{ __('Register') }}</flux:button>
            </form>
        </div>
    </flux:card>
</div>
@endsection
