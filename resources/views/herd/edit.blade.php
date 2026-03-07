@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
            <div>
                <flux:heading size="xl" level="1">My Herd</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Which (and how many) elePHPants do you have?</flux:text>
            </div>
            <flux:button href="{{ route('herds.show', auth()->user()->username) }}" variant="primary" wire:navigate class="shrink-0">View public herd</flux:button>
        </div>
        <div id="stats">
            @livewire('herd-stats')
        </div>

        <livewire:species-search mode="herd" defer />
    </div>
@endsection
