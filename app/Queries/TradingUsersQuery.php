<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use App\Queries\TradingUsersQueryOption;
use App\User;
use Illuminate\Database\Eloquent\Collection;

final class TradingUsersQuery
{
    private const USER_MATCHING_LIMIT = 5;

    public function fetchAll(User $user, TradingUsersQueryOption $options = null, int $limit = self::USER_MATCHING_LIMIT)
    {
        $userElephpants = $user->elephpants;

        $userAvailable = $this->keepTradableElephpants($userElephpants);

        if ($userAvailable->count() === 0) {
            return null;
        }

        $traders = $this->fetchTraders($userElephpants, $options, $limit);

        foreach ($traders as $trader) {
            $this->addInterestedElephpants($trader, $userAvailable);
        }

        return $traders;
    }

    private function fetchTraders(Collection $userElephpants, TradingUsersQueryOption $options = null, int $limit)
    {
        $userElephpants = $userElephpants->pluck('id');

        $elephpantsQuery = function ($query) use ($userElephpants, $options) {
            $query->whereNotIn('id', $userElephpants);
            $this->handleTradingOptions($query, $options);
        };


        $authUserId = auth()->id();

        return User::query()
            ->with([
                'elephpants',
                'elephpantsToTrade' => $elephpantsQuery,
            ])

            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->whereExists(function ($query) use ($authUserId) {
                $this->getQueryLoggedInUserHasSpareElephpantForThatUser($query, $authUserId);
            })
            ->paginate($limit);
    }

    public function getQueryLoggedInUserHasSpareElephpantForThatUser($query, $authUserId)
    {
        // the loggedin user has a spare elephpant
        $query
            ->from('elephpant_user','eu4')
            ->where('eu4.user_id', $authUserId)
            ->where('eu4.quantity', '>', 1)
            ->whereNotExists(function ($query5) use ($authUserId) {
                // that the listed user doesn't have
                $query5
                    ->from('elephpant_user','eu5')
                    ->whereRaw(Elephpant::raw('eu5.elephpant_id = eu4.elephpant_id'))
                    ->whereRaw(Elephpant::raw('eu5.user_id = users.id'));

            });
    }

    private function keepTradableElephpants(Collection $userElephpants)
    {
        return $userElephpants->filter(function (Elephpant $elephpant) {
            return $elephpant->pivot->quantity > 1;
        });
    }

    private function addInterestedElephpants(User $trader, Collection $userAvailable): void
    {
        $traderElephpants = $trader->elephpants->pluck('id')->toArray();

        $interests = $userAvailable->filter(function (Elephpant $elephpant) use ($traderElephpants) {
            return !in_array($elephpant->id, $traderElephpants);
        });

        $trader->elephpantsInterested = $interests;
    }

    private function handleTradingOptions($query, $options)
    {
        if (!$options) {
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

    public function getQueryUserWithSpareElephpantId($query, $spareElephpantId)
    {
        $query->whereExists(function($query2) use ($spareElephpantId) {
            // That user has a spare wanted Elephpant
            $query2->select('elephpant_id')
                ->from('elephpant_user as eu2')
                ->whereRaw(User::raw('eu2.user_id = elephpant_user.user_id'))
                ->where('eu2.elephpant_id', $spareElephpantId)
                ->where('eu2.quantity', '>', 1);
            }
        );
    }

    public function getQueryUserWhoLackElephpantId($query, $lackElephpantId)
    {
        $query->whereNotExists(function($query2) use ($lackElephpantId) {
            // That user doesn't have the lack ElephpantId
            $query2->select('elephpant_id')
                ->from('elephpant_user as eu2')
                ->whereRaw(User::raw('eu2.user_id = elephpant_user.user_id'))
                ->where('eu2.elephpant_id', $lackElephpantId);

            }
        );
    }
}
