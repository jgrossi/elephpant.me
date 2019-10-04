<?php

namespace App\Http\Controllers;

use App\Queries\ElephpantsQuery;
use Illuminate\Support\Facades\Auth;

class HerdController extends Controller
{
    public function edit(ElephpantsQuery $elephpantsQuery)
    {
        $elephpants = $elephpantsQuery->fetchAllOrderedAndGrouped();
        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.edit', compact('elephpants', 'userElephpants', 'stats'));
    }
}
