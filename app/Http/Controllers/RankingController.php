<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class RankingController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('ranking.index');
    }
}
