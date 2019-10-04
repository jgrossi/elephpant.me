<?php

namespace App\Http\Controllers;

use App\Photo;

class PhotoController extends Controller
{
    public function create()
    {
        return view('photos.create');
    }

    public function store()
    {
        Photo::create([
            'url' => request('url'),
            'user_id' => auth()->id(),
        ]);

        session()->flash('status-success', 'Photo saved. Thank you!');

        return redirect()->route('home');
    }
}
