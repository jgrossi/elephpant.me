<?php

declare(strict_types=1);

namespace App\Queries;

use App\Country;
use App\User;
use Illuminate\Support\Collection;

final class CountryCollectorsQuery
{
    /**
     * Countries with at least one public collector, with a collector count each.
     *
     * @return Collection<int, array{code: string, name: string, collectors: int}>
     */
    public function fetchAll(): Collection
    {
        $collectorsByCode = User::query()
            ->public()
            ->select('country_code')
            ->selectRaw('COUNT(*) AS collectors')
            ->groupBy('country_code')
            ->pluck('collectors', 'country_code');

        $countries = Country::query()
            ->whereIn('code', $collectorsByCode->keys())
            ->get()
            ->keyBy('code');

        return $collectorsByCode
            ->map(fn (int $collectors, string $code): array => [
                'code'       => $code,
                'name'       => $countries[$code]->name ?? $code,
                'collectors' => $collectors,
            ])
            ->values()
            ->sortBy([
                fn (array $a, array $b): int => $b['collectors'] <=> $a['collectors'],
                fn (array $a, array $b): int => $a['name'] <=> $b['name'],
            ])
            ->values();
    }
}
