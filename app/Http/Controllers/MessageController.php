<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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

    public function history(MessagesQuery $query)
    {
        $interlocutorMessages = $query->getMessagesWithLoggedInUser();
        $loggedInUser = Auth::user();

        return view('messages.history', compact('interlocutorMessages', 'loggedInUser'));
    }
}
