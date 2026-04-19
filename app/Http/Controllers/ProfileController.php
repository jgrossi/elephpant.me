<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        return view('profile.edit', ['user' => $user]);
    }

    public function update(ProfileRequest $request)
    {
        $section = $request->input('section', 'account');
        $user = Auth::user();

        if ($section === 'account') {
            $user->fill($request->only('name', 'email', 'country_code', 'username'));
        }

        if ($section === 'password' && $request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($section === 'public_profile') {
            $user->fill($request->only('twitter', 'mastodon'));
        }

        $user->save();
        session()->flash('status-success', 'Your profile was saved successfully.');

        return redirect()->route('profile.edit');
    }
}
