<?php

namespace App\Livewire;

use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class TradeMessage extends Component
{
    public User $receiverUser;

    public string $message = "Hey, just saw you're looking for an elePHPant I have double. Let's trade?";

    public bool $sent = false;

    protected $rules = [
        'message' => 'required',
    ];

    public function mount(User $receiverUser): void
    {
        $this->receiverUser = $receiverUser;
    }

    public function send(): void
    {
        $this->validate();

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->receiverUser->id,
            'message'     => $this->message,
        ]);

        Mail::send(new UserMessage(auth()->user(), $this->receiverUser, $this->message));

        $this->message = '';
        $this->sent = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.trade-message');
    }
}
