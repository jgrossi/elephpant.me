<?php

namespace App\Http\Controllers;

use App\Elephpant;
use App\Http\Requests\AdoptionRequest;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    public function update(AdoptionRequest $request, Elephpant $elephpant)
    {
        $user = Auth::user();
        $user->adopt($elephpant, $request->get('quantity'));

        return response()->json(null, 204);
    }
}
