@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 pt-8">
    <flux:card class="space-y-6">
        <flux:heading size="lg">{{ __('Login') }}</flux:heading>
        <div>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <flux:field>
                    <flux:label>{{ __('E-Mail Address') }}</flux:label>
                    <flux:input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Password') }}</flux:label>
                    <flux:input type="password" name="password" id="password" required autocomplete="current-password" />
                    <flux:error name="password" />
                </flux:field>

                <flux:checkbox name="remember" id="remember" :checked="old('remember')" label="{{ __('Remember Me') }}" />

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button type="submit" variant="primary">{{ __('Login') }}</flux:button>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:underline">{{ __('Forgot Your Password?') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </flux:card>
</div>
@endsection
