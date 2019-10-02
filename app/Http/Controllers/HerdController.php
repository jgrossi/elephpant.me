<?php

namespace App\Http\Controllers;

use App\Elephpant;

class HerdController extends Controller
{
    public function edit()
    {
        $elephpants = Elephpant::query()->orderBy('year')->get()->groupBy('year');
        $myElephpants = auth()->user()->elephpants;

        return view('herd.edit', compact('myElephpants', 'elephpants'));
    }
}
