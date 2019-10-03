<?php

namespace App\Http\Controllers;

use App\Elephpant;

class HerdController extends Controller
{
    public function edit()
    {
        $elephpants = Elephpant::query()
            ->orderBy('year')
            ->orderBy('name')
            ->get()
            ->groupBy('year');

        $userElephpants = auth()->user()
            ->elephpants
            ->mapWithKeys(function (Elephpant $elephpant) {
                return [$elephpant->id => $elephpant->pivot->quantity];
            })
            ->toArray();

        return view('herd.edit', compact('elephpants', 'userElephpants'));
    }
}
