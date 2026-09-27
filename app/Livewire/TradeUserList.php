<?php

namespace App\Livewire;

use App\Queries\TradingUsersQuery;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TradeUserList extends Component
{
    use WithPagination;

    public ?string $country = null;
    public ?string $search = null;

    /** @var array<string, array{name?: string, flag?: string}> */
    public array $countries = [];

    protected $queryString = ['country' => ['except' => ''], 'search' => ['except' => '']];

    public function mount(?string $country = null, ?string $search = null, array $countries = []): void
    {
        $this->country = $country ?? request('country');
        $this->search = $search ?? request('search');
        $this->countries = $countries;
    }

    public function updatedCountry(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        return app(TradingUsersQuery::class)
            ->fetchAll(auth()->user(), null, 5, $this->country, false, $this->search);
    }
}

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.trade-user-list');
    }
}
