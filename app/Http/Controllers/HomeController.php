<?php

namespace App\Http\Controllers;

use App\Elephpant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $elephpants = Elephpant::query()
            ->filter($request)
            ->withCount('users')
            ->withoutGlobalScope('nonPrototype')
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('home', compact('elephpants'));
    }
}
