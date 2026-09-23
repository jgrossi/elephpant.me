<?php

namespace App\Livewire;

use App\Mail\UserMessage;
use App\Message;
use App\Queries\MessagesQuery;
use App\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ConversationThread extends Component
{
    public User $otherUser;

    public string $dateFormat;

    public string $body = '';

    public function mount(User $otherUser, string $dateFormat): void
    {
        $this->otherUser = $otherUser;
        $this->dateFormat = $dateFormat;
    }

    public function send(): void
    {
        $this->validate([
            'body' => ['required', 'string'],
        ]);

        Message::query()->create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->otherUser->id,
            'message'     => $this->body,
        ]);

        Mail::send(new UserMessage(auth()->user(), $this->otherUser, $this->body));

        $this->reset('body');
    }

    public function render(MessagesQuery $query): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.conversation-thread', [
            'messages' => $query->getMessagesWithLoggedInUserAndSomeoneElse($this->otherUser->id),
        ]);
    }
}
