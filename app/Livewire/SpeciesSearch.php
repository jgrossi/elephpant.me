<?php

namespace App\Livewire;

use App\Elephpant;
use App\Queries\ElephpantsQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Defer;
use Livewire\Component;

/**
 * @property-read Collection<int, Elephpant> $filteredElephpants
 * @property-read Collection<int, Collection<int, Elephpant>> $filteredElephpantsGrouped
 * @property-read array<int, array{type: string, count: int}> $tradePossibilities
 * @property-read int $speciesCount
 * @property-read int $collectedSpecies
 */
#[Defer]
class SpeciesSearch extends Component
{
    public string $q = '';

    /** @var 'catalog'|'herd' */
    public string $mode = 'catalog';

    public ?int $limit = null;

    /** @var array<int, int>|null */
    public ?array $userElephpants = null;

    public ?int $totalSpecies = null;

    protected $queryString = ['q' => ['except' => '']];

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

    public function getFilteredElephpantsGroupedProperty(): Collection
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
        ]);
    }
}
