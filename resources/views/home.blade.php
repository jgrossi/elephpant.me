@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto bg-zinc-200 dark:bg-zinc-800 rounded-xl px-6 py-12 md:py-16 mb-10 md:mb-14 text-center">
        <flux:heading size="xl" level="1" class="text-zinc-900 dark:text-zinc-100">Welcome, collector! 🐘</flux:heading>
        <flux:text class="mt-4 text-base text-zinc-700 dark:text-zinc-300">
            Here is the right place for your elePHPants.<br>
            You can <flux:link href="{{ route('herds.edit') }}" wire:navigate class="font-medium underline underline-offset-2 decoration-2 decoration-zinc-400 hover:decoration-zinc-600 dark:hover:decoration-zinc-300 rounded px-0.5 -mx-0.5">update your herd</flux:link>, see <flux:link href="{{ route('rankings.index') }}" wire:navigate class="font-medium underline underline-offset-2 decoration-2 decoration-zinc-400 hover:decoration-zinc-600 dark:hover:decoration-zinc-300 rounded px-0.5 -mx-0.5">ranking</flux:link> (global / per country), and
            <flux:link href="{{ route('trades.index') }}" wire:navigate class="font-medium underline underline-offset-2 decoration-2 decoration-zinc-400 hover:decoration-zinc-600 dark:hover:decoration-zinc-300 rounded px-0.5 -mx-0.5">find people to trade</flux:link>.
        </flux:text>
        @guest
            <div class="flex flex-wrap justify-center gap-3 mt-6">
                <flux:button href="{{ route('register') }}" variant="primary" wire:navigate>Register</flux:button>
                <flux:button href="{{ route('login') }}" variant="outline" wire:navigate>Login</flux:button>
            </div>
        @endguest
    </div>

    <livewire:species-search mode="catalog" defer />
@endsection
