<?php

namespace App\Http\Controllers;

use App\Elephpant;

class HomeController extends Controller
{
    public function index()
    {
        $elephpants = Elephpant::query()->orderBy('year', 'desc')->orderBy('name', 'asc')->get();

        return view('home', compact('elephpants'));
    }
}
