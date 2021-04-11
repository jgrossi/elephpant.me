<?php

declare(strict_types=1);

namespace App\Queries;

use App\User;
use PragmaRX\Countries\Package\Countries;
use PragmaRX\Countries\Package\Support\Collection;

final class CountriesQuery
{
    public function fetchAll(): Collection
    {
        return $this->collection();
    }

    public function fetchAllPlucked(): Collection
    {
        return $this->collection()
            ->pluck('name.common', 'cca3');
    }

    public function filterOnlyUsedCountries($allCountries): Collection
    {
        $usedCodes = [];
        $usedCodesObjects = $query = User::query()
                ->selectRaw('distinct(country_code) as cc')
                ->from('users as u')
                ->get();
        foreach ($usedCodesObjects as $codeObject) {
            $usedCodes[] = $codeObject->cc;
        }

        $usedCountries = clone $allCountries;

        foreach ($usedCountries as $key => $code) {
            if (!in_array($key, $usedCodes)) {
                unset($usedCountries[$key]);
            }
        }
        return $usedCountries;
    }

    public function flags(): Collection
    {
        return $this->collection()
            ->pluck('flag.flag-icon', 'cca3');
    }

    private function collection(): Collection
    {
        return (new Countries())->all()
            ->sortBy('name.common');
    }
}
