<?php

namespace App\Http\Controllers;

use App\Elephpant;
use App\Http\Requests\AdoptionRequest;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

class AdoptionController extends Controller
{
    #[Group('Herds')]
    #[Endpoint(
        title: 'Set a quantity in your herd',
        description: 'Sets how many of an elePHPant the authenticated collector owns. A quantity of 0 removes it from the herd.',
        authenticated: true,
    )]
    #[UrlParam('elephpant_id', 'integer', 'elePHPant ID.', example: 1)]
    #[BodyParam('quantity', 'integer', 'How many you own.', example: 2)]
    #[Response(status: 204, description: 'Quantity saved.')]
    #[Response(content: ['message' => 'Unauthenticated.'], status: 401, description: 'Missing or invalid token.')]
    public function update(AdoptionRequest $request, Elephpant $elephpant)
    {
        $request->user()->adopt($elephpant, $request->input('quantity'));

        return response()->json(null, 204);
    }
}
