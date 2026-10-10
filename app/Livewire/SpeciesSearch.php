<?php

namespace App\Livewire;

use App\Elephpant;
use App\Format;
use App\Queries\ElephpantsQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Defer;
use Livewire\Component;

/**
 * @property-read Collection<int, Elephpant> $filteredElephpants
 * @property-read Collection<int, Collection<int, Elephpant>> $filteredElephpantsGrouped
 * @property-read array<int, array{type: string, count: int}> $tradePossibilities
 * @property-read int $speciesCount
 * @property-read int $collectedSpecies
 * @property-read array{collected: int, total: int} $filteredHerdProgress
 * @property-read int $catalogTotal
 * @property-read bool $isCatalogPreview
 * @property-read array<int, string> $availableYears
 * @property-read array<int, string> $availableColors
 * @property-read array<string, string> $availableSizes
 * @property-read array<string, string> $availableOwnership
 * @property-read int $activeFilterCount
 * @property-read Collection<int|string, Collection<int, Elephpant>> $searchedElephpantsGrouped
 * @property-read array<string, array<string, int>> $facetCounts
 */
#[Defer]
class SpeciesSearch extends Component
{
    public string $q = '';

    /** @var 'catalog'|'herd' */
    public string $mode = 'catalog';

    /** @var array<int, 'years'|'colors'|'sizes'|'ownership'> */
    private const array FILTERS = ['years', 'colors', 'sizes', 'ownership'];

    public ?int $limit = null;

    /** @var array<int, int>|null */
    public ?array $userElephpants = null;

    public ?int $totalSpecies = null;

    /** @var array<int, string> */
    public array $years = [];

    /** @var array<int, string> */
    public array $colors = [];

    /** @var array<int, string> */
    public array $sizes = [];

    /** @var array<int, 'owned'|'not-owned'> */
    public array $ownership = [];

    protected $queryString = [
        'q'         => ['except' => ''],
        'years'     => ['except' => []],
        'colors'    => ['except' => []],
        'sizes'     => ['except' => []],
        'ownership' => ['except' => []],
    ];

    public function mount(string $mode = 'catalog', ?int $limit = null, ?array $userElephpants = null, ?int $totalSpecies = null): void
    {
        $this->q = (string) request()->input('q', '');
        $this->mode = $mode === 'herd' ? 'herd' : 'catalog';
        $this->limit = $limit;
        $this->totalSpecies = $totalSpecies;

        if ($this->mode === 'herd' && $totalSpecies !== null) {
            $this->userElephpants = $userElephpants ?? [];
        }
    }

    /** @return array<int, int> */
    private function userElephpantQuantities(): array
    {
        if ($this->userElephpants === null && $this->mode === 'herd' && $this->totalSpecies === null && Auth::check()) {
            $this->userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();
        }

        return $this->userElephpants ?? [];
    }

    public function getFilteredElephpantsProperty(): Collection
    {
        if ($this->mode === 'herd') {
            return collect();
        }

        $query = Elephpant::query()->orderBy('year', 'desc')->orderBy('id', 'desc');

        if ($this->q !== '') {
            $term = '%'.$this->q.'%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'LIKE', $term)
                    ->orWhere('description', 'LIKE', $term)
                    ->orWhere('sponsor', 'LIKE', $term)
                    ->orWhere('year', 'LIKE', $term);
            });
        } elseif ($this->limit !== null) {
            $query->limit($this->limit);
        }

        return $query->get();
    }

    /**
     * Herd elePHPants matching the search box, before the dropdown filters are applied.
     * Memoized per request because the underlying query is expensive.
     */
    #[Computed]
    public function searchedElephpantsGrouped(): Collection
    {
        if ($this->mode !== 'herd' || !Auth::check()) {
            return collect();
        }

        $elephpants = app(ElephpantsQuery::class)->fetchAllOrderedAndGrouped();

        if ($this->q !== '') {
            $term = strtolower($this->q);
            $elephpants = $elephpants->map(fn ($group) => $group->filter(fn ($elephpant): bool => str_contains(strtolower((string) $elephpant->name), $term)
                || str_contains(strtolower((string) ($elephpant->description ?? '')), $term)
                || str_contains(strtolower((string) ($elephpant->sponsor ?? '')), $term)
                || str_contains((string) $elephpant->year, $term))->values())->filter->isNotEmpty();
        }

        return $elephpants;
    }

    public function getFilteredElephpantsGroupedProperty(): Collection
    {
        $elephpants = $this->searchedElephpantsGrouped;

        if ($this->activeFilterCount > 0) {
            $quantities = $this->userElephpantQuantities();
            $elephpants = $elephpants->map(fn ($group) => $group->filter(fn (Elephpant $elephpant): bool => $this->matchesFilters($elephpant, $quantities))->values())->filter->isNotEmpty();
        }

        return $elephpants;
    }

    /**
     * For each filter, how many elePHPants each option would match given the search and every other filter.
     *
     * @return array<string, array<string, int>>
     */
    public function getFacetCountsProperty(): array
    {
        $quantities = $this->userElephpantQuantities();
        $elephpants = $this->searchedElephpantsGrouped->flatten(1);
        $counts = array_fill_keys(self::FILTERS, []);

        foreach (self::FILTERS as $filter) {
            foreach ($elephpants as $elephpant) {
                if ($this->matchesFilters($elephpant, $quantities, except: $filter)) {
                    $value = $this->filterValue($elephpant, $filter, $quantities);
                    $counts[$filter][$value] = ($counts[$filter][$value] ?? 0) + 1;
                }
            }
        }

        return $counts;
    }

    /**
     * @param array<int, int>                           $quantities
     * @param 'years'|'colors'|'sizes'|'ownership'|null $except
     */
    private function matchesFilters(Elephpant $elephpant, array $quantities, ?string $except = null): bool
    {
        foreach (self::FILTERS as $filter) {
            if ($filter === $except) {
                continue;
            }

            if ($this->{$filter} === []) {
                continue;
            }

            if (!in_array($this->filterValue($elephpant, $filter, $quantities), array_map(strval(...), $this->{$filter}), true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param 'years'|'colors'|'sizes'|'ownership' $filter
     * @param array<int, int>                      $quantities
     */
    private function filterValue(Elephpant $elephpant, string $filter, array $quantities): string
    {
        return match ($filter) {
            'years'     => (string) $elephpant->year,
            'colors'    => (string) $elephpant->color,
            'sizes'     => $elephpant->format->value,
            'ownership' => ($quantities[$elephpant->id] ?? 0) > 0 ? 'owned' : 'not-owned',
        };
    }

    /** @return array<int, string> */
    public function getAvailableYearsProperty(): array
    {
        return Elephpant::query()
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn ($year): string => (string) $year)
            ->all();
    }

    /** @return array<int, string> */
    public function getAvailableColorsProperty(): array
    {
        return Elephpant::query()
            ->distinct()
            ->orderBy('color')
            ->pluck('color')
            ->all();
    }

    /** @return array<string, string> */
    public function getAvailableSizesProperty(): array
    {
        return collect(Format::cases())
            ->mapWithKeys(fn (Format $format): array => [$format->value => ucfirst($format->value)])
            ->all();
    }

    /** @return array<string, string> */
    public function getAvailableOwnershipProperty(): array
    {
        return [
            'owned'     => 'Owned',
            'not-owned' => 'Not owned',
        ];
    }

    public function getActiveFilterCountProperty(): int
    {
        return count($this->years) + count($this->colors) + count($this->sizes) + count($this->ownership);
    }

    public function clearFilters(): void
    {
        $this->reset('years', 'colors', 'sizes', 'ownership');
    }

    public function incrementQuantity(int $elephpantId): void
    {
        $quantities = $this->userElephpantQuantities();
        $this->saveQuantity($elephpantId, ($quantities[$elephpantId] ?? 0) + 1);
    }

    public function decrementQuantity(int $elephpantId): void
    {
        $quantities = $this->userElephpantQuantities();
        $quantity = $quantities[$elephpantId] ?? 0;

        if ($quantity > 0) {
            $this->saveQuantity($elephpantId, $quantity - 1);
        }
    }

    public function updatedUserElephpants($value, string $key): void
    {
        $this->saveQuantity((int) $key, (int) $value);
    }

    private function saveQuantity(int $elephpantId, int $quantity): void
    {
        if ($this->mode !== 'herd' || !Auth::check()) {
            return;
        }

        $quantity = max(0, $quantity);
        $quantities = $this->userElephpantQuantities();

        if ($quantity === 0) {
            unset($quantities[$elephpantId]);
        } else {
            $quantities[$elephpantId] = $quantity;
        }

        $this->userElephpants = $quantities;

        $elephpant = Elephpant::findOrFail($elephpantId);
        Auth::user()->adopt($elephpant, $quantity);

        $unique = count($quantities);
        $total = array_sum($quantities);

        $this->dispatch('refreshStats', stats: [
            'unique'         => $unique,
            'total'          => $total,
            'double'         => $total - $unique,
            'userElephpants' => $quantities,
        ]);
    }

    public function getTradePossibilitiesProperty(): array
    {
        if ($this->mode !== 'herd') {
            return [];
        }

        return $this->prepareTradePossibilities(
            $this->filteredElephpantsGrouped,
            $this->userElephpantQuantities()
        );
    }

    public function getSpeciesCountProperty(): int
    {
        if ($this->mode === 'catalog') {
            return $this->filteredElephpants->count();
        }

        return $this->filteredElephpantsGrouped->flatten()->unique('id')->count();
    }

    /**
     * The herd species that match the search and filters, and how many of them the user owns.
     *
     * @return array{collected: int, total: int}
     */
    public function getFilteredHerdProgressProperty(): array
    {
        $matchingElephpants = $this->filteredElephpantsGrouped->flatten()->unique('id');
        $quantities = $this->userElephpantQuantities();

        return [
            'collected' => $matchingElephpants->filter(fn (Elephpant $elephpant): bool => ($quantities[$elephpant->id] ?? 0) > 0)->count(),
            'total'     => $matchingElephpants->count(),
        ];
    }

    public function getCollectedSpeciesProperty(): int
    {
        if ($this->mode !== 'herd') {
            return 0;
        }

        return count($this->userElephpantQuantities());
    }

    public function getCatalogTotalProperty(): int
    {
        if ($this->mode !== 'catalog') {
            return 0;
        }

        return Elephpant::count();
    }

    public function getIsCatalogPreviewProperty(): bool
    {
        return $this->mode === 'catalog'
            && $this->limit !== null
            && $this->q === ''
            && $this->catalogTotal > $this->speciesCount;
    }

    public function clearSearch(): void
    {
        $this->q = '';
    }

    public function placeholder(array $params = []): \Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.species-search-skeleton', $params);
    }

    private function prepareTradePossibilities(Collection $elephpants, array $userElephpants): array
    {
        $tradePossibilites = [];
        /** @var Collection<int, Elephpant> $group */
        foreach ($elephpants as $group) {
            foreach ($group as $elephpant) {
                if (($userElephpants[$elephpant->id] ?? 0) == 0 && (string) ($elephpant->possible_senders ?? '') !== '') {
                    $tradePossibilites[$elephpant->id] = [
                        'type'  => 'senders',
                        'count' => count(explode(',', (string) $elephpant->possible_senders)),
                    ];
                } elseif (($userElephpants[$elephpant->id] ?? 0) > 1 && (string) ($elephpant->possible_receivers ?? '') !== '') {
                    $tradePossibilites[$elephpant->id] = [
                        'type'  => 'receivers',
                        'count' => count(explode(',', (string) $elephpant->possible_receivers)),
                    ];
                }
            }
        }

        return $tradePossibilites;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.species-search', [
            'elephpants'         => $this->filteredElephpants,
            'elephpantsGrouped'  => $this->filteredElephpantsGrouped,
            'userElephpants'     => $this->userElephpantQuantities(),
            'tradePossibilities' => $this->tradePossibilities,
            'speciesCount'       => $this->speciesCount,
            'catalogTotal'       => $this->catalogTotal,
            'isCatalogPreview'   => $this->isCatalogPreview,
            'totalSpecies'       => $this->mode === 'herd' ? ($this->totalSpecies ?? Elephpant::count()) : 0,
            'collectedSpecies'   => $this->collectedSpecies,
            'filteredHerdProgress' => $this->mode === 'herd' ? $this->filteredHerdProgress : ['collected' => 0, 'total' => 0],
            'facetCounts'        => $this->mode === 'herd' ? $this->facetCounts : [],
            'filterOptions'      => $this->mode === 'herd' ? [
                'years'     => ['label' => 'Year', 'options' => array_combine($this->availableYears, $this->availableYears)],
                'colors'    => ['label' => 'Color', 'options' => array_combine($this->availableColors, $this->availableColors)],
                'sizes'     => ['label' => 'Size', 'options' => $this->availableSizes],
                'ownership' => ['label' => 'Owned', 'options' => $this->availableOwnership],
            ] : [],
            'activeFilterCount'  => $this->activeFilterCount,
        ]);
    }
}
