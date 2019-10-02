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
                return [$elephpant->id => $elephpant->herd->quantity];
            })
            ->toArray();

        return view('herd.edit', compact('elephpants', 'userElephpants'));
    }
}
