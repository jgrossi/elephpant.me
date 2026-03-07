@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 pt-8">
    <flux:card class="space-y-6">
        <flux:heading size="lg">{{ __('Reset Password') }}</flux:heading>
        <div>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <flux:field>
                    <flux:label>{{ __('E-Mail Address') }}</flux:label>
                    <flux:input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Password') }}</flux:label>
                    <flux:input type="password" name="password" id="password" required autocomplete="new-password" />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Confirm Password') }}</flux:label>
                    <flux:input type="password" name="password_confirmation" id="password-confirm" required autocomplete="new-password" />
                </flux:field>

                <flux:button type="submit" variant="primary">{{ __('Reset Password') }}</flux:button>
            </form>
        </div>
    </flux:card>
</div>
@endsection
