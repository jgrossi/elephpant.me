<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Authentication', 'Exchange your username and password for an API token.')]
class TokenController extends Controller
{
    #[Endpoint(
        title: 'Create an API token',
        description: 'Returns a token to send as `Authorization: Bearer {token}` on authenticated endpoints. Limited to 5 attempts per minute.',
    )]
    #[BodyParam('username', 'string', 'Your username.', example: 'john')]
    #[BodyParam('password', 'string', 'Your password.', example: 'secret')]
    #[BodyParam('device_name', 'string', 'A name for the token, so you can tell your tokens apart.', example: 'my-script')]
    #[Response(content: ['token' => '1|abcdef123456'], status: 201, description: 'Token created.')]
    #[Response(content: ['message' => 'The provided credentials are incorrect.', 'errors' => ['username' => ['The provided credentials are incorrect.']]], status: 422, description: 'Wrong username or password.')]
    #[Response(content: ['message' => 'Too Many Attempts.'], status: 429, description: 'Too many attempts.')]
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username'    => ['required', 'string'],
            'password'    => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::whereUsername($data['username'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken($data['device_name'])->plainTextToken,
        ], 201);
    }
}
