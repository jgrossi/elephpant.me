<?php

namespace App\Http\Controllers;

use App\Elephpant;

class ElephpantController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $total = Elephpant::count();

        return view('elephpant.index', ['total' => $total]);
    }
}
