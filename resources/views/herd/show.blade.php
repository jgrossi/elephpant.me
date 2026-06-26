@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-wrap items-start gap-4 py-6 md:py-8">
            <x-user-profile :user="$user" :countries="$countries" />
        </div>

        <div id="stats">
            @include('herd._stats')
        </div>

        <flux:heading size="lg" class="text-zinc-600 dark:text-zinc-300 mb-4">Collection</flux:heading>

        <livewire:public-herd-collection :username="$user->username" defer />

        @if(!is_null($possibleTrades))
            <flux:heading size="lg" class="text-zinc-600 dark:text-zinc-300 mt-8 mb-4">Possible Trades</flux:heading>
            <div>
                @if(count($possibleTrades))
                    <div class="space-y-4">
                    @foreach($possibleTrades as $possibleUser)
                        @include('trade._possible_deal', ['user' => $possibleUser])
                    @endforeach
                    </div>
                @else
                    <flux:callout variant="info">We didn't find any possible trade.</flux:callout>
                @endif
            </div>
        @endif
    </div>
@endsection
