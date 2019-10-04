<?php

declare(strict_types=1);

namespace App\Queries;

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
