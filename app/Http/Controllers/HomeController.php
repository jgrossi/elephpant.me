<?php

namespace App\Http\Controllers;

use App\Elephpant;

class HomeController extends Controller
{
    public function index()
    {
        $elephpants = Elephpant::inRandomOrder()->limit(32)->get();

        return view('home', compact('elephpants'));
    }
}
