<?php

namespace App\Http\Controllers;

use App\Country;
use App\Conversation;
use App\Http\Requests\MessageRequest;
use App\Mail\UserMessage;
use App\Message;
use App\Queries\MessagesQuery;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function destroy(Request $request, string $username): RedirectResponse
    {
        $otherUser = User::whereUsername($username)->firstOrFail();
        $user = $request->user();

        abort_unless($user instanceof User, 403);
        abort_unless($user->getLastMessageWith($otherUser) !== null, 403);

        Conversation::withTrashed()->firstOrCreate([
            'user_id'       => $user->id,
            'other_user_id' => $otherUser->id,
        ])->delete();

        return redirect()
            ->route('messages.conversations')
            ->with('status', 'Conversation deleted.');
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
