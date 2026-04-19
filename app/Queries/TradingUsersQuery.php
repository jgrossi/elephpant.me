<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use App\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class TradingUsersQuery
{
    private const int USER_MATCHING_LIMIT = 5;

    public function fetchAll(User $user, ?TradingUsersQueryOption $options = null, int $limit = self::USER_MATCHING_LIMIT, ?string $country = null, bool $simplePaginate = false)
    {
        $userElephpants = $user->elephpants;

        $userAvailable = $this->keepTradableElephpants($userElephpants);

        if ($userAvailable->count() === 0) {
            return null;
        }

        $traders = $this->fetchTraders($userElephpants, $options, $limit, $country, $simplePaginate);

        foreach ($traders as $trader) {
            $this->addInterestedElephpants($trader, $userAvailable);
        }

        return $traders;
    }

    /**
     * Country codes that have at least one user the given user can trade with.
     * Used to limit the Trade Area country dropdown to only useful options.
     *
     * @return array<int, string>
     */
    public function getCountryCodesWithTraders(User $user, ?TradingUsersQueryOption $options = null): array
    {
        $userElephpants = $user->elephpants;
        $userAvailable = $this->keepTradableElephpants($userElephpants);

        if ($userAvailable->count() === 0) {
            return [];
        }

        $userElephpantIds = $userElephpants->pluck('id');
        $elephpantsQuery = function ($query) use ($userElephpantIds, $options): void {
            $query->whereNotIn('id', $userElephpantIds);
            $this->handleTradingOptions($query, $options);
        };

        $authUserId = auth()->id();

        return User::query()
            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->whereExists(function ($query) use ($authUserId): void {
                $this->getQueryLoggedInUserHasSpareElephpantForThatUser($query, $authUserId);
            })
            ->select('country_code')
            ->distinct()
            ->pluck('country_code')
            ->filter()
            ->values()
            ->toArray();
    }

    private function fetchTraders(EloquentCollection $userElephpants, ?TradingUsersQueryOption $options = null, int $limit = 0, ?string $country = null, bool $simplePaginate = false)
    {
        $userElephpants = $userElephpants->pluck('id');

        $elephpantsQuery = function ($query) use ($userElephpants, $options): void {
            $query->whereNotIn('id', $userElephpants);
            $this->handleTradingOptions($query, $options);
        };

        $authUserId = auth()->id();

        $countryFilter = $country !== null && $country !== ''
            ? $country
            : request()->input('country');

        $query = User::query()
            ->with([
                'elephpants',
                'elephpantsToTrade' => $elephpantsQuery,
            ])
            ->when($countryFilter !== null && $countryFilter !== '', fn ($q) => $q->where('country_code', $countryFilter))
            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->whereExists(function ($q) use ($authUserId): void {
                $this->getQueryLoggedInUserHasSpareElephpantForThatUser($q, $authUserId);
            });

        return $simplePaginate
            ? $query->simplePaginate($limit)
            : $query->paginate($limit);
    }

    /**
     * Total number of traders matching the same filters as fetchAll (for display in Trade Area).
     */
    public function countTraders(User $user, ?string $country = null): int
    {
        $userElephpants = $user->elephpants;
        $userAvailable = $this->keepTradableElephpants($userElephpants);

        if ($userAvailable->count() === 0) {
            return 0;
        }

        $userElephpants = $userElephpants->pluck('id');
        $elephpantsQuery = function ($query) use ($userElephpants): void {
            $query->whereNotIn('id', $userElephpants);
            $this->handleTradingOptions($query, null);
        };

        $authUserId = auth()->id();
        $countryFilter = $country !== null && $country !== '' ? $country : request()->input('country');

        return (int) User::query()
            ->when($countryFilter !== null && $countryFilter !== '', fn ($q) => $q->where('country_code', $countryFilter))
            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->whereExists(function ($q) use ($authUserId): void {
                $this->getQueryLoggedInUserHasSpareElephpantForThatUser($q, $authUserId);
            })
            ->count();
    }

    public function getQueryLoggedInUserHasSpareElephpantForThatUser($query, $authUserId): void
    {
        $query
            ->from('elephpant_user', 'eu4')
            ->where('eu4.user_id', $authUserId)
            ->where('eu4.quantity', '>', 1)
            ->whereNotExists(function ($query5): void {
                $query5
                    ->from('elephpant_user', 'eu5')
                    ->whereColumn('eu5.elephpant_id', 'eu4.elephpant_id')
                    ->whereColumn('eu5.user_id', 'users.id');
            });
    }

    /**
     * @param EloquentCollection<int, Elephpant> $userElephpants
     *
     * @return EloquentCollection<int, Elephpant>
     */
    private function keepTradableElephpants(EloquentCollection $userElephpants): EloquentCollection
    {
        return $userElephpants->filter(fn (Elephpant $elephpant): bool => $elephpant->pivot->quantity > 1);
    }

    /**
     * @param EloquentCollection<int, Elephpant> $userAvailable
     */
    private function addInterestedElephpants(User $trader, EloquentCollection $userAvailable): void
    {
        $traderElephpants = $trader->elephpants->pluck('id')->toArray();

        $interests = $userAvailable->filter(fn (Elephpant $elephpant): bool => !in_array($elephpant->id, $traderElephpants));

        /** @var EloquentCollection<int, Elephpant> $interests */
        $trader->elephpantsInterested = $interests;
    }

    private function handleTradingOptions($query, ?\App\Queries\TradingUsersQueryOption $options): void
    {
        if (!$options instanceof \App\Queries\TradingUsersQueryOption) {
            return;
        }

        if ($options->targetUserId) {
            $query->where('user_id', $options->targetUserId);
        }

        if ($options->withSpareElephpantId) {
            $this->getQueryUserWithSpareElephpantId($query, $options->withSpareElephpantId);
        }

        if ($options->lackElephpantId) {
            $this->getQueryUserWhoLackElephpantId($query, $options->lackElephpantId);
        }
    }

    public function fetchAllForUser(User $user, int $targetUserId)
    {
        $tradeOptions = new TradingUsersQueryOption();
        $tradeOptions->targetUserId = $targetUserId;

        return $this->fetchAll($user, $tradeOptions);
    }

    public function fetchAllOnlyIfHeHasElephpant(User $user, int $elephpantId)
    {
        $tradeOptions = new TradingUsersQueryOption();
        $tradeOptions->withSpareElephpantId = $elephpantId;

        return $this->fetchAll($user, $tradeOptions);
    }

    public function fetchAllWhoLackElephpant(User $user, int $elephpantId)
    {
        $tradeOptions = new TradingUsersQueryOption();
        $tradeOptions->lackElephpantId = $elephpantId;

        return $this->fetchAll($user, $tradeOptions);
    }

    public function getQueryUserWithSpareElephpantId($query, $spareElephpantId): void
    {
        $query->whereExists(function ($query2) use ($spareElephpantId): void {
            $query2->select('elephpant_id')
                ->from('elephpant_user as eu2')
                ->whereColumn('eu2.user_id', 'elephpant_user.user_id')
                ->where('eu2.elephpant_id', $spareElephpantId)
                ->where('eu2.quantity', '>', 1);
        });
    }

    public function getQueryUserWhoLackElephpantId($query, $lackElephpantId): void
    {
        $query->whereNotExists(function ($query2) use ($lackElephpantId): void {
            $query2->select('elephpant_id')
                ->from('elephpant_user as eu2')
                ->whereColumn('eu2.user_id', 'elephpant_user.user_id')
                ->where('eu2.elephpant_id', $lackElephpantId);
        });
    }
}
