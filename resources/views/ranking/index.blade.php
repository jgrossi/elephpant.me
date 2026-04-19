@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
            <div>
                <flux:heading size="xl" level="1">Ranking</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Here you can find the top 50 collectors in the PHP community.</flux:text>
            </div>
        </div>

        @livewire('ranking-table')
    </div>
@endsection
