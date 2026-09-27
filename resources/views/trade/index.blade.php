@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
            <div>
                <flux:heading size="xl" level="1" class="text-zinc-600 dark:text-zinc-300">Trade Area</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Looking for new elePHPants? Take a look at these possibilities.</flux:text>
            </div>
        </div>

        @if($useLivewireList ?? false)
            @livewire('trade-user-list', ['countries' => $countries])
        @else
            <div class="space-y-3 mb-6">
                <form id="trade-filter-form" action="{{ request()->url() }}" method="get" class="flex flex-wrap items-center gap-2">
                    @if(isset($trades))
                        <flux:input type="search" name="search" value="{{ $search }}" placeholder="Search traders" class="min-w-[220px]" />
                        <flux:button type="submit" icon="magnifying-glass">Search</flux:button>
                        <flux:button href="{{ request()->url() }}" variant="ghost" icon="x-mark">Clear filters</flux:button>
                    @endif
                    <flux:select name="country" id="country" class="min-w-[200px]" onchange="document.getElementById('trade-filter-form').submit()">
                        <option value="">-- All Traders --</option>
                        @foreach($countries as $code => $current)
                            <option value="{{ $code }}" {{ $country === $code ? 'selected' : '' }}>
                                {{ $current['name'] ?? '' }}
                            </option>
                        @endforeach
                    </flux:select>
                </form>
                @if($country && !isset($countries[$country]))
                    <flux:callout variant="danger">This country is not in our records.</flux:callout>
                @endif
            </div>

            @php($tradeResults = $trades ?? $users)

            @if(!$tradeResults)
                <flux:callout variant="info">You don't have any double elePHPant to trade yet.</flux:callout>
            @elseif(count($tradeResults))
                <flux:callout variant="info" icon="information-circle" heading="Found {{ $tradeResults->total() }} {{ \Illuminate\Support\Str::plural('user', $tradeResults->total()) }} that can trade with you." class="mb-4" />
                @foreach($tradeResults as $user)
                    <flux:card class="space-y-0">
                        <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                            @include('trade._user', ['user' => $user, 'countries' => $countries])
                        </div>
                        <div class="p-4">
                            @include('trade._possible_deal')
                        </div>
                    </flux:card>
                @endforeach
                <div class="flex justify-center mt-6">
                    {{ $tradeResults->appends(request()->query())->links() }}
                </div>
            @else
                <flux:callout variant="info">No users found that can trade with you.</flux:callout>
            @endif
        @endif
    </div>
@endsection
