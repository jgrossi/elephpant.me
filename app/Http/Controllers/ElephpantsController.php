<?php

namespace App\Http\Controllers;

use App\Elephpant;
use Illuminate\Http\Request;

class ElephpantsController extends Controller
{
    public function index()
    {
        $elephpants = Elephpant::query()->orderBy('id', 'desc')->get();

        return view('elephpant.index', compact('elephpants'));
    }
}
