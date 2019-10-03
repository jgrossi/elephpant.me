<?php

namespace App\Http\Controllers;

use App\Elephpant;
use Illuminate\Support\Facades\Auth;

class HerdController extends Controller
{
    public function edit()
    {
        $elephpants = Elephpant::query()->orderBy('year')->orderBy('name')->get()->groupBy('year');
        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.edit', compact('elephpants', 'userElephpants', 'stats'));
    }
}
