<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\MessageRequest;
use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Queries\MessagesQuery;

class MessageController extends Controller
{
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

        return view('messages.index', ['messages' => $messages]);
    }

    public function conversation(string $username, MessagesQuery $query): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $otherUser = User::with('elephpants')->whereUsername($username)->firstOrFail();
        $messages = $query->getMessagesWithLoggedInUserAndSomeoneElse($otherUser->id);

        $dateFormat = match (auth()->user()->country_code) {
            'USA', 'PHL' => 'F jS Y g:ia',
            'CHN', 'HUN', 'IRN', 'JPN', 'KOR', 'LTU', 'PRK', 'SWE' => 'Y F j H:i',
            default => 'j F Y H:i',
        };

        return view('messages.conversation', [
            'messages' => $messages,
            'dateFormat' => $dateFormat,
            'otherUser' => $otherUser,
            'countries' => Country::forDropdown([$otherUser->country_code]),
        ]);
    }
}
