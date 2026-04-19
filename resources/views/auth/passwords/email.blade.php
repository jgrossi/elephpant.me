@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 pt-8">
    <flux:card class="space-y-6">
        <flux:heading size="lg">{{ __('Reset Password') }}</flux:heading>
        <div>
            @if (session('status'))
                <flux:callout variant="success" icon="check-circle" heading="{{ session('status') }}" class="mb-4" />
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <flux:field>
                    <flux:label>{{ __('E-Mail Address') }}</flux:label>
                    <flux:input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                    <flux:error name="email" />
                </flux:field>

                <flux:button type="submit" variant="primary">{{ __('Send Password Reset Link') }}</flux:button>
            </form>
        </div>
    </flux:card>
</div>
@endsection
