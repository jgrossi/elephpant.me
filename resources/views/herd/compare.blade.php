@extends('layouts.app')

@section('content')
    <div
        class="max-w-6xl mx-auto space-y-6"
        x-data="{
            lightboxImage: '',
            lightboxCaption: '',
            filterValues: @js($filterValues),
            filters: { years: [], colors: [], sizes: [] },
            init() {
                const params = new URLSearchParams(window.location.search);

                for (const [key, value] of params) {
                    const filter = key.match(/^(years|colors|sizes)\[\d*\]$/)?.[1];

                    if (filter && !this.filters[filter].includes(value)) {
                        this.filters[filter].push(value);
                    }
                }

                this.$watch('filters', () => this.syncFiltersToUrl());
            },
            syncFiltersToUrl() {
                const url = new URL(window.location.href);

                [...url.searchParams.keys()]
                    .filter((key) => /^(years|colors|sizes)\[\d*\]$/.test(key))
                    .forEach((key) => url.searchParams.delete(key));

                Object.entries(this.filters).forEach(([filter, values]) => {
                    values.forEach((value, index) => url.searchParams.append(`${filter}[${index}]`, value));
                });

                history.replaceState(history.state, '', url);
            },
            matches(id, except = null) {
                const values = this.filterValues[id];

                return Object.entries(this.filters).every(([filter, selected]) => {
                    return filter === except || selected.length === 0 || selected.includes(values[filter]);
                });
            },
            get facetCounts() {
                const counts = { years: {}, colors: {}, sizes: {} };

                Object.keys(counts).forEach((filter) => {
                    Object.entries(this.filterValues).forEach(([id, values]) => {
                        if (this.matches(id, filter)) {
                            counts[filter][values[filter]] = (counts[filter][values[filter]] ?? 0) + 1;
                        }
                    });
                });

                return counts;
            },
            get activeFilterCount() {
                return Object.values(this.filters).reduce((total, selected) => total + selected.length, 0);
            },
            get matchingCount() {
                return Object.keys(this.filterValues).filter((id) => this.matches(id)).length;
            },
            clearFilters() {
                this.filters = { years: [], colors: [], sizes: [] };
            },
            openLightbox(image, caption) {
                this.lightboxImage = image;
                this.lightboxCaption = caption;
                $dispatch('modal-show', { name: 'elephpant-image' });
            },
        }"
    >
        <div class="flex flex-col sm:flex-row items-stretch justify-center gap-4 py-6 md:py-8">
            <div class="flex-1 min-w-0 flex justify-center">
                <x-user-profile :user="$user0" :countries="$countries" />
            </div>

            <div class="flex items-center justify-center px-2">
                <span class="text-sm font-semibold">vs</span>
            </div>

            <div class="flex-1 min-w-0 flex justify-center">
                <x-user-profile :user="$user1" :countries="$countries" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @foreach($filterOptions as $property => $filter)
                @php $initialCounts = collect($filterValues)->countBy($property); @endphp
                <flux:dropdown>
                    <flux:button
                        size="sm"
                        icon:trailing="chevron-down"
                        variant="outline"
                        x-bind:class="filters.{{ $property }}.length > 0 ? 'bg-[var(--color-accent)]! hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]! text-[var(--color-accent-foreground)]! border-black/10! dark:border-transparent!' : ''"
                    >
                        <span x-text="'{{ $filter['label'] }}' + (filters.{{ $property }}.length > 0 ? ` (${filters.{{ $property }}.length})` : '')">{{ $filter['label'] }}</span>
                    </flux:button>
                    <flux:menu class="max-h-80 overflow-y-auto">
                        <flux:menu.checkbox.group x-model="filters.{{ $property }}" data-compare-filter="{{ $property }}">
                            @foreach($filter['options'] as $value => $label)
                                @php $jsValue = \Illuminate\Support\Js::from((string) $value); @endphp
                                <flux:menu.checkbox
                                    value="{{ $value }}"
                                    keep-open
                                    x-bind:disabled="(facetCounts.{{ $property }}[{{ $jsValue }}] ?? 0) === 0 && ! filters.{{ $property }}.includes({{ $jsValue }})"
                                >{{ $label }} (<span x-text="facetCounts.{{ $property }}[{{ $jsValue }}] ?? 0">{{ $initialCounts[(string) $value] ?? 0 }}</span>)</flux:menu.checkbox>
                            @endforeach
                        </flux:menu.checkbox.group>
                    </flux:menu>
                </flux:dropdown>
            @endforeach
            <flux:button size="sm" variant="ghost" icon="x-mark" x-show="activeFilterCount > 0" x-on:click="clearFilters()" style="display: none">Clear filters</flux:button>
        </div>

        <flux:table>
            <flux:table.columns sticky class="sticky top-0 bg-zinc-50 dark:bg-zinc-900 z-10">
                <flux:table.column sticky>Image</flux:table.column>
                <flux:table.column sortable>Sponsor</flux:table.column>
                <flux:table.column sortable>Name</flux:table.column>
                <flux:table.column sortable>Description</flux:table.column>
                <flux:table.column sortable>Year</flux:table.column>
                <flux:table.column sortable>{{ $user0->name }}</flux:table.column>
                <flux:table.column sortable>{{ $user1->name }}</flux:table.column>
            </flux:table.columns>

            <tbody id="comparison-table">
            @foreach ($elephpants as $elephpant)
                @php
                    $user0Quantity = (int) ($user0->elephpants->firstWhere('id', $elephpant->id)?->pivot->quantity ?? 0);
                    $user1Quantity = (int) ($user1->elephpants->firstWhere('id', $elephpant->id)?->pivot->quantity ?? 0);
                @endphp
                <flux:table.row :key="$elephpant->id" x-show="matches({{ $elephpant->id }})">
                    <flux:table.cell sticky class="bg-zinc-50 dark:bg-zinc-900">
                        <button
                            type="button"
                            class="block overflow-hidden rounded border border-zinc-200 dark:border-zinc-700 cursor-zoom-in transition hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                            data-elephpant-lightbox
                            data-image="{{ asset('storage/elephpants/'.$elephpant->image) }}"
                            data-alt="{{ $elephpant->name.' – '.$elephpant->description }}"
                            aria-label="{{ __('View larger image of :name', ['name' => $elephpant->name]) }}"
                            @click="openLightbox($el.dataset.image, $el.dataset.alt)"
                        >
                            <img
                                src="{{ asset('storage/elephpants/'.$elephpant->image) }}"
                                alt="{{ $elephpant->description }}"
                                width="40"
                                height="40"
                                class="block size-10 object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                        </button>
                    </flux:table.cell>
                    <flux:table.cell>{{ $elephpant->sponsor }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->formattedName() }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->description }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->year }}</flux:table.cell>
                    <flux:table.cell class="whitespace-normal wrap-break-word {{ $user0Quantity === 0 ? 'text-zinc-300! dark:text-zinc-600!' : '' }}">{{ $user0Quantity }}</flux:table.cell>
                    <flux:table.cell class="whitespace-normal wrap-break-word {{ $user1Quantity === 0 ? 'text-zinc-300! dark:text-zinc-600!' : '' }}">{{ $user1Quantity }}</flux:table.cell>
                </flux:table.row>
            @endforeach
            </tbody>
        </flux:table>

        <div
            x-show="matchingCount === 0"
            style="display: none"
            class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50/50 dark:bg-zinc-800/30 px-6 py-16 text-center"
        >
            <flux:heading size="lg" class="mb-2">No species found</flux:heading>
            <flux:text variant="subtle" class="max-w-sm">No species match your filters. Try adjusting or clearing them.</flux:text>
        </div>

        <flux:modal name="elephpant-image" class="w-full max-w-lg">
            <flux:heading size="lg" class="sr-only">{{ __('Elephpant image') }}</flux:heading>
            <img
                id="elephpant-lightbox-image"
                src=""
                alt=""
                class="mx-auto h-auto max-h-[75vh] w-auto max-w-full rounded-lg object-contain"
                x-bind:src="lightboxImage"
                x-bind:alt="lightboxCaption"
            >
            <flux:text class="mt-3 text-center text-sm text-zinc-600 dark:text-zinc-400" x-text="lightboxCaption"></flux:text>
        </flux:modal>
    </div>
@endsection
