<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->get('password'));
        }

        Auth::user()->fill($data)->save();
        session()->flash('status-success', 'Your profile was saved successfully.');

        return redirect()->route('profile.edit');
    }
}
