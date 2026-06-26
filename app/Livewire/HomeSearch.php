<?php

namespace App\Livewire;

use App\Elephpant;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;

/**
 * @property-read EloquentCollection<int, Elephpant> $filteredElephpants
 */
class HomeSearch extends Component
{
    public string $q = '';

    protected $queryString = ['q' => ['except' => '']];

    public function mount(): void
    {
        $this->q = (string) request()->input('q', '');
    }

    public function getFilteredElephpantsProperty()
    {
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

    public function clearSearch(): void
    {
        $this->q = '';
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.home-search', [
            'elephpants' => $this->filteredElephpants,
        ]);
    }
}
