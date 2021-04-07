<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use Illuminate\Database\Eloquent\Collection;

final class ElephpantsQuery
{
    public function fetchAllOrderedAndGrouped(): Collection
    {
        $query = Elephpant::query()
            ->orderBy('year')
            ->orderBy('name')
            ->get()
            ->groupBy('year');


        $loggedUserId = auth()->id();
        return Elephpant::query()
            ->select()
            ->addSelect([
                'possible_receivers' => function ($query) use ($loggedUserId) {
                    $this->getPossibleReceivers($query, $loggedUserId);
                },
                'possible_senders' => function ($query) use ($loggedUserId) {
                    $this->getPossibleSenders($query, $loggedUserId);
                }
            ])
            ->from('elephpants as e')
            ->orderby('year')
            ->orderby('name')
            ->get()
            ->groupBy('year');
    }

    public function getPossibleSenders($query, $authUserId)
    {
        $query->select([Elephpant::raw("group_concat(u.username) as nb_users") ])
        ->from('elephpant_user as eu')
        ->join('users as u', 'u.id', '=', 'eu.user_id')
        ->whereRaw(Elephpant::raw('eu.elephpant_id = e.id'))
        ->where('eu.quantity', '>', 1)
        ->whereNotIn('eu.user_id', [$authUserId])
        ->whereExists(function ($query2) use ($authUserId) {
            // Other users with spare elephpants
            $query2->select('elephpant_id')
                ->from('elephpant_user as eu2')
                ->where('eu2.user_id', $authUserId)
                ->where('eu2.quantity', '>', 1)
                ->whereNotExists(function ($query3) {
                    // That
                    $query3->from('elephpant_user as eu3')
                    ->whereRaw(Elephpant::raw('eu3.elephpant_id = eu2.elephpant_id'))
                    ->whereRaw(Elephpant::raw('eu3.user_id = eu.user_id'));
                });
        });
    }

    public function getPossibleReceivers($query, $authUserId)
    {
        $query->select([Elephpant::raw("group_concat(distinct u.id) as nb_users") ])
        ->from('users as u')
        ->whereNotIn('u.id', [$authUserId])
        ->whereNotExists(function ($query2) use ($authUserId) {
            // User doesn't have this elephpant
            $query2
                ->from('elephpant_user', 'eu2')
                ->whereRaw(Elephpant::raw('eu2.elephpant_id = e.id'))
                ->whereRaw(Elephpant::raw('eu2.user_id = u.id'));
        })
        ->whereExists(function ($query3) use ($authUserId) {
            // Logged in user has this elephpant to spare
            $query3
                ->from('elephpant_user', 'eu3')
                ->whereRaw(Elephpant::raw('eu3.elephpant_id = e.id'))
                ->where('eu3.quantity', '>', 1)
                ->where('eu3.user_id', $authUserId);
        })
        ->whereExists(function ($query4) use ($authUserId) {
            // User has an elephpant to spare, that is not the same, that the logged in user doesn't have
            $query4
                ->from('elephpant_user', 'eu4')
                ->whereRaw(Elephpant::raw('eu4.elephpant_id != e.id'))
                ->whereRaw(Elephpant::raw('eu4.user_id = u.id'))
                ->where('eu4.quantity', '>', 1)
                ->whereNotExists(function ($query5) use ($authUserId) {
                    $query5
                        ->from('elephpant_user', 'eu5')
                        ->whereRaw(Elephpant::raw('eu5.elephpant_id = eu4.elephpant_id'))
                        ->where('eu5.user_id', $authUserId);
                });
        });
    }
}
