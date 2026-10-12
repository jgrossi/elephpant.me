<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\UserMessage;
use App\Message;
use App\Queries\MessagesQuery;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Messages', 'Your messages with other collectors, such as trade requests.')]
#[Authenticated]
class MessageController extends Controller
{
    #[Endpoint(
        title: 'List your conversations',
        description: 'Returns one entry per collector you have exchanged messages with, newest conversation first, with the latest message.',
    )]
    #[Response(content: ['message' => 'Unauthenticated.'], status: 401, description: 'Missing or invalid token.')]
    #[ResponseField('data[].user.username', 'string', 'The other collector.')]
    #[ResponseField('data[].message_count', 'integer', 'Messages in the conversation.')]
    #[ResponseField('data[].latest_message.from', 'string', 'Username of the sender.')]
    public function index(Request $request, MessagesQuery $query): JsonResponse
    {
        $me = $request->user();

        $conversations = $query->getConversations()
            ->map(fn (Message $message): array => [
                'user'            => $this->userData($message->sender_id === $me->id ? $message->receiver : $message->sender),
                'message_count'   => (int) $message->message_count,
                'started_at'      => Carbon::parse($message->conversation_started_at)->toIso8601String(),
                'last_message_at' => Carbon::parse($message->last_message_at)->toIso8601String(),
                'latest_message'  => $this->messageData($message),
            ])
            ->values();

        return response()->json(['data' => $conversations]);
    }

    #[Endpoint(
        title: 'Get a conversation',
        description: 'Returns all messages between you and another collector, oldest first.',
    )]
    #[UrlParam('username', 'string', 'The other collector.', example: 'john')]
    #[Response(content: ['message' => 'Unauthenticated.'], status: 401, description: 'Missing or invalid token.')]
    #[Response(content: ['message' => 'No query results for model [App\\User].'], status: 404, description: 'No collector with that username.')]
    #[ResponseField('messages[].from', 'string', 'Username of the sender.')]
    public function show(string $username, MessagesQuery $query): JsonResponse
    {
        $other = User::whereUsername($username)->firstOrFail();

        return response()->json([
            'user'     => $this->userData($other),
            'messages' => $query->getMessagesWithLoggedInUserAndSomeoneElse($other->id)
                ->map(fn (Message $message): array => $this->messageData($message))
                ->values(),
        ]);
    }

    #[Endpoint(
        title: 'Send a message',
        description: 'Sends a message, such as a trade request, to another collector. They also get it by email. Limited to 10 messages per minute.',
    )]
    #[UrlParam('username', 'string', 'The collector to message.', example: 'john')]
    #[BodyParam('message', 'string', 'The message.', example: 'Hi! Want to trade spares?')]
    #[Response(status: 204, description: 'Message sent.')]
    #[Response(content: ['message' => 'Unauthenticated.'], status: 401, description: 'Missing or invalid token.')]
    #[Response(content: ['message' => 'No query results for model [App\\User].'], status: 404, description: 'No collector with that username.')]
    public function store(Request $request, string $username): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $receiver = User::whereUsername($username)->firstOrFail();

        Message::query()->create([
            'sender_id'   => $request->user()->id,
            'receiver_id' => $receiver->id,
            'message'     => $data['message'],
        ]);

        Mail::send(new UserMessage($request->user(), $receiver, $data['message']));

        return response()->json(null, 204);
    }

    private function userData(User $user): array
    {
        return [
            'username' => $user->username,
            'name'     => $user->name,
            'avatar'   => $user->avatar(),
            'herd_url' => route('herds.show', $user->username),
        ];
    }

    private function messageData(Message $message): array
    {
        return [
            'id'         => $message->id,
            'from'       => $message->sender->username,
            'to'         => $message->receiver->username,
            'message'    => $message->message,
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }
}
