<?php

namespace App\Http\Controllers;

use App\Elephpant;
use Illuminate\Http\Request;

class ElephpantController extends Controller
{
    public function index()
    {
        $elephpants = Elephpant::query()->orderBy('year', 'desc')->orderBy('name', 'asc')->get();

        return view('elephpant.index', compact('elephpants'));
    }
}
