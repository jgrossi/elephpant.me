<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\MessageRequest;
use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use App\Queries\MessagesQuery;

class MessageController extends Controller
{
    #[Group('Messages', 'Messages between collectors, such as trade requests.')]
    #[Endpoint(
        title: 'Send a message',
        description: 'Sends a message, such as a trade request, to another collector. They also get it by email. Limited to 10 messages per minute.',
        authenticated: true,
    )]
    #[BodyParam('receiver_id', 'integer', 'User ID of the collector to message.', example: 2)]
    #[BodyParam('message', 'string', 'The message.', example: 'Hi! Want to trade spares?')]
    #[Response(status: 204, description: 'Message sent.')]
    #[Response(content: ['message' => 'Unauthenticated.'], status: 401, description: 'Missing or invalid token.')]
    public function store(MessageRequest $request)
    {
        $data = $request->only(['message', 'receiver_id']);
        $receiver = User::find($data['receiver_id']);

        $message = new Message($data);
        $message->sender_id = $request->user()->id;
        $message->save();

        Mail::send(new UserMessage($request->user(), $receiver, $data['message']));

        return response()->json(null, 204);
    }

    public function conversations(MessagesQuery $query): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $messages = $query->getConversations();

        return view('messages.index', [
            'messages' => $messages,
            'dateFormat' => $this->dateFormatFor(auth()->user()),
        ]);
    }

    public function conversation(string $username): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $otherUser = User::with('elephpants')->whereUsername($username)->firstOrFail();

        return view('messages.conversation', [
            'dateFormat' => $this->dateFormatFor(auth()->user()),
            'otherUser' => $otherUser,
            'countries' => Country::forDropdown([$otherUser->country_code]),
        ]);
    }

    private function dateFormatFor(User $user): string
    {
        return match ($user->country_code) {
            'USA', 'PHL' => 'F jS Y g:ia',
            'CHN', 'HUN', 'IRN', 'JPN', 'KOR', 'LTU', 'PRK', 'SWE' => 'Y F j H:i',
            default => 'j F Y H:i',
        };
    }
}
