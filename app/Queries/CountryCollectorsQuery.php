<?php

declare(strict_types=1);

namespace App\Queries;

use App\Country;
use App\User;

final class CountryCollectorsQuery
{
    /**
     * Countries with at least one public collector, with a collector count each.
     *
     * Counts the same people the ranking does: a public herd holding at least one
     * elePHPant. Registering an account is not collecting, and counting registrations
     * here put thousands of empty herds in countries that rank nobody.
     *
     * @return list<array{code: string, code_2: string|null, name: string, collectors: int}>
     */
    public function fetchAll(): array
    {
        $collectorsByCode = User::query()
            ->public()
            ->has('elephpants')
            ->select('country_code')
            ->selectRaw('COUNT(*) AS collectors')
            ->groupBy('country_code')
            ->pluck('collectors', 'country_code');

        /** @var array<string, Country> $countries A code a user holds may not be in the country list at all */
        $countries = Country::query()
            ->whereIn('code', $collectorsByCode->keys())
            ->get()
            ->keyBy('code')
            ->all();

        return $collectorsByCode
            ->map(function (int $collectors, string $code) use ($countries): array {
                $country = $countries[$code] ?? null;

                return [
                    'code'       => $code,
                    'code_2'     => $country?->code_2,
                    'name'       => $country->name ?? $code,
                    'collectors' => $collectors,
                ];
            })
            ->values()
            ->sortBy([
                fn (array $a, array $b): int => $b['collectors'] <=> $a['collectors'],
                fn (array $a, array $b): int => $a['name'] <=> $b['name'],
            ])
            ->values()
            ->all();
    }
}
