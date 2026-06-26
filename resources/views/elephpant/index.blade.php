@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
            <div>
                <flux:heading size="xl" level="1">Species</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Here you can find all existent species. There are a total of <strong>{{ $total }} species</strong> to collect.</flux:text>
            </div>
            <flux:button href="{{ route('herds.edit') }}" variant="primary" wire:navigate class="shrink-0">Go to "My Herd" page</flux:button>
        </div>

        <livewire:species-search mode="catalog" defer />
    </div>
@endsection
