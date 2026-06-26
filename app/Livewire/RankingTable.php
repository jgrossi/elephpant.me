<?php

namespace App\Livewire;

use App\Country;
use App\Queries\RankedUsersQuery;
use App\Queries\UsersCountryQuery;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read array<string, array{name: string, flag: string}> $countries
 * @property-read Collection<int, \App\User> $users
 */
class RankingTable extends Component
{
    public string $country = '';

    protected $queryString = ['country' => ['except' => '']];

    public function mount(): void
    {
        $this->country = (string) request()->input('country', '');
    }

    public function selectCountry(string $code): void
    {
        $this->country = $code;
    }

    public function getCountriesProperty(): array
    {
        $usersQuery = new UsersCountryQuery()->fetchAll();
        $countryCodes = $usersQuery->unique('country_code')->pluck('country_code')->toArray();

        return Country::forDropdown($countryCodes);
    }

    public function getUsersProperty(): Collection
    {
        return app(RankedUsersQuery::class)->fetchAll($this->country ?: null);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.ranking-table', [
            'countries' => $this->countries,
            'users'     => $this->users,
        ]);
    }
}
