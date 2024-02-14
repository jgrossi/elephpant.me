<?php

namespace App\Http\Controllers;

use App\Elephpant;
use Illuminate\Http\Request;

class ElephpantController extends Controller
{
    public function index(Request $request)
    {
        $elephpants = Elephpant::query()->filter($request)->orderBy('year', 'desc')->orderBy('id', 'desc')->get();

        return view('elephpant.index', compact('elephpants'));
    }
}
