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

    /** @var array<string, array{name?: string, flag?: string}> */
    public array $countries = [];

    protected $queryString = ['country' => ['except' => '']];

    public function mount(?string $country = null, array $countries = []): void
    {
        $this->country = $country ?? request('country');
        $this->countries = $countries;
    }

    public function updatedCountry(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        $query = app(TradingUsersQuery::class);

        return $query->fetchAll(auth()->user(), null, 5, $this->country, true);
    }

    #[Computed]
    public function totalTraders(): int
    {
        return app(TradingUsersQuery::class)->countTraders(auth()->user(), $this->country);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.trade-user-list');
    }
}
