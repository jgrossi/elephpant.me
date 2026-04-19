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
 * @property-read array<int, int> $userElephpants
 * @property-read array<int, array{type: string, count: int}> $tradePossibilities
 * @property-read int $speciesCount
 * @property-read int $totalSpecies
 * @property-read int $collectedSpecies
 */
#[Defer]
class SpeciesSearch extends Component
{
    public string $q = '';

    /** @var 'catalog'|'herd' */
    public string $mode = 'catalog';

    /** @var array<int, int>|null Updated from refreshStats event so progress bars update without re-query. */
    public ?array $userElephpantsFromEvent = null;

    protected $queryString = ['q' => ['except' => '']];

    protected $listeners = ['refreshStats' => 'onRefreshStats'];

    public function mount(string $mode = 'catalog'): void
    {
        $this->q = (string) request()->input('q', '');
        $this->mode = $mode === 'herd' ? 'herd' : 'catalog';
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

    public function getUserElephpantsProperty(): array
    {
        if ($this->mode !== 'herd' || !Auth::check()) {
            return [];
        }

        if ($this->userElephpantsFromEvent !== null) {
            return $this->userElephpantsFromEvent;
        }

        return Auth::user()->elephpantsWithQuantity()->toArray();
    }

    public function onRefreshStats($stats = null): void
    {
        if (is_array($stats) && array_key_exists('userElephpants', $stats)) {
            $this->userElephpantsFromEvent = $stats['userElephpants'];
        }
    }

    public function getTradePossibilitiesProperty(): array
    {
        if ($this->mode !== 'herd') {
            return [];
        }

        return $this->prepareTradePossibilities(
            $this->filteredElephpantsGrouped,
            $this->userElephpants
        );
    }

    public function getSpeciesCountProperty(): int
    {
        if ($this->mode === 'catalog') {
            return $this->filteredElephpants->count();
        }

        return $this->filteredElephpantsGrouped->flatten()->unique('id')->count();
    }

    public function getTotalSpeciesProperty(): int
    {
        if ($this->mode !== 'herd') {
            return 0;
        }

        return Elephpant::count();
    }

    public function getCollectedSpeciesProperty(): int
    {
        if ($this->mode !== 'herd') {
            return 0;
        }

        return count($this->userElephpants);
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
            'userElephpants'     => $this->userElephpants,
            'tradePossibilities' => $this->tradePossibilities,
            'speciesCount'       => $this->speciesCount,
            'totalSpecies'       => $this->totalSpecies,
            'collectedSpecies'   => $this->collectedSpecies,
        ]);
    }
}
