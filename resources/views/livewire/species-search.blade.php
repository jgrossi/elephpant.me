<div class="w-full max-w-6xl mx-auto">
    <div class="flex flex-nowrap items-center gap-3 mb-6">
        <flux:input
            type="text"
            wire:model.live.debounce.300ms="q"
            placeholder="{{ $mode === 'herd' ? 'Search species' : 'Search for Elephpants' }}"
            class="min-w-[180px] flex-1 max-w-md"
        />
        @if($mode === 'herd' && $totalSpecies > 0)
            <div class="flex ml-auto w-1/4 min-w-[140px] max-w-[240px] items-center gap-3 shrink-0">
                <flux:progress
                    :value="round(($collectedSpecies / $totalSpecies) * 100)"
                    max="100"
                    class="h-2 flex-1 min-w-0"
                />
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 tabular-nums shrink-0">Species Found: {{ $collectedSpecies }} of {{ $totalSpecies }}</flux:text>
            </div>
        @elseif($mode === 'catalog')
            <flux:text class="font-medium shrink-0 whitespace-nowrap ml-auto">Species Found: {{ $speciesCount }}</flux:text>
        @endif
    </div>

    @if($mode === 'catalog')
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @if($elephpants->count())
                @foreach($elephpants as $elephpant)
                    @include('elephpant._single_box', compact('elephpant'))
                @endforeach
            @else
                @include('partials._no_elephpants_found')
            @endif
        </div>
    @else
        @if($speciesCount > 0)
            @foreach($elephpantsGrouped as $year => $group)
                @php
                    $totalInYear = $group->count();
                    $collectedInYear = $group->filter(fn ($e) => ($userElephpants[$e->id] ?? 0) > 0)->count();
                @endphp
                <div class="{{ $loop->first ? '' : 'pt-10 mt-10 border-t border-zinc-200 dark:border-zinc-700' }}">
                    <flux:heading size="lg" class="text-zinc-600 dark:text-zinc-300 mb-2">{{ $year }}</flux:heading>
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <flux:field class="flex-1 min-w-[200px] max-w-md">
                            <flux:label class="sr-only">{{ $year }} collection progress</flux:label>
                            <flux:progress
                                :value="$totalInYear > 0 ? round(($collectedInYear / $totalInYear) * 100) : 0"
                                max="100"
                                class="h-2"
                            />
                        </flux:field>
                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 tabular-nums">{{ $collectedInYear }} of {{ $totalInYear }}</flux:text>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($group as $elephpant)
                            @include('herd._elephpant_card', [
                                'elephpant' => $elephpant,
                                'userElephpants' => $userElephpants,
                                'tradePossibilites' => $tradePossibilities,
                            ])
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50/50 dark:bg-zinc-800/30 px-6 py-16 text-center">
                <flux:heading size="lg" class="mb-2">No species found</flux:heading>
                <flux:text variant="subtle" class="max-w-sm">No species match your search. Try a different term or clear the search box.</flux:text>
            </div>
        @endif
    @endif
</div>
