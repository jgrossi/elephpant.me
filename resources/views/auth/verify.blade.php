@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 pt-8">
    <flux:card class="space-y-4">
        <flux:heading size="lg">{{ __('Verify Your Email Address') }}</flux:heading>
        <div class="space-y-4">
            @if (session('resent'))
                <flux:callout variant="success" icon="check-circle" heading="{{ __('A fresh verification link has been sent to your email address.') }}" />
            @endif

            <flux:text>
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <flux:button type="submit" variant="link" class="p-0 m-0 align-baseline">
                        {{ __('click here to request another') }}
                    </flux:button>.
                </form>
            </flux:text>
        </div>
    </flux:card>
</div>
@endsection
