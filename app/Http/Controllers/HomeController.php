<?php

namespace App\Http\Controllers;

use App\Photo;

class HomeController extends Controller
{
    public function index()
    {
        $photos = Photo::inRandomOrder()->limit(50)->get();

        return view('home', compact('photos'));
    }
}
