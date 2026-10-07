<div class="w-full">
    @if($mode === 'herd')
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <flux:input
                type="text"
                size="sm"
                icon="magnifying-glass"
                wire:model.live.debounce.300ms="q"
                placeholder="Search species"
                aria-label="Search species"
                class="w-full sm:w-56 sm:flex-none"
            />
            @foreach($filterOptions as $property => $filter)
                @php $selectedCount = count($this->{$property}); @endphp
                <flux:dropdown wire:key="filter-{{ $property }}">
                    <flux:button size="sm" icon:trailing="chevron-down" :variant="$selectedCount > 0 ? 'primary' : 'outline'">
                        {{ $filter['label'] }}{{ $selectedCount > 0 ? " ({$selectedCount})" : '' }}
                    </flux:button>
                    <flux:menu class="max-h-80 overflow-y-auto">
                        <flux:menu.checkbox.group wire:model.live="{{ $property }}">
                            @foreach($filter['options'] as $value => $label)
                                @php
                                    $matchCount = $facetCounts[$property][(string) $value] ?? 0;
                                    $isSelected = in_array((string) $value, array_map('strval', $this->{$property}), true);
                                @endphp
                                <flux:menu.checkbox
                                    wire:key="filter-{{ $property }}-{{ $value }}"
                                    value="{{ $value }}"
                                    keep-open
                                    :disabled="$matchCount === 0 && ! $isSelected"
                                >{{ $label }} ({{ $matchCount }})</flux:menu.checkbox>
                            @endforeach
                        </flux:menu.checkbox.group>
                    </flux:menu>
                </flux:dropdown>
            @endforeach
            @if($activeFilterCount > 0)
                <flux:button size="sm" variant="ghost" icon="x-mark" wire:click="clearFilters" :loading="false">Clear filters</flux:button>
            @endif
            <div wire:loading.flex wire:target="q, years, colors, sizes, ownership, clearFilters" role="status" class="items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                <flux:icon.loading class="size-4" />
                Updating results…
            </div>
            @if($totalSpecies > 0)
                <div class="flex w-full lg:w-auto lg:flex-1 items-center justify-end gap-3 mt-2 lg:mt-0">
                    <flux:progress
                        :value="round(($collectedSpecies / $totalSpecies) * 100)"
                        max="100"
                        class="h-2 flex-1 min-w-12 lg:max-w-40"
                    />
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 tabular-nums shrink-0">Species Found: {{ $collectedSpecies }} of {{ $totalSpecies }}</flux:text>
                </div>
            @endif
        </div>
    @else
        <div class="flex flex-nowrap items-center gap-3 mb-6">
            <flux:input
                type="text"
                wire:model.live.debounce.300ms="q"
                placeholder="Search for Elephpants"
                class="min-w-[180px] flex-1 max-w-md"
            />
            <flux:text class="font-medium shrink-0 whitespace-nowrap ml-auto">
                @if($isCatalogPreview)
                    Showing {{ $speciesCount }} of {{ $catalogTotal }}
                @else
                    Species Found: {{ $speciesCount }}
                @endif
            </flux:text>
        </div>
    @endif

    <div
        wire:loading.class="opacity-50 pointer-events-none"
        wire:target="q, years, colors, sizes, ownership, clearFilters"
        class="transition-opacity"
    >
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
                    <flux:text variant="subtle" class="max-w-sm">No species match your search. Try a different term, clear the search box, or adjust your filters.</flux:text>
                </div>
            @endif
        @endif
    </div>
</div>
