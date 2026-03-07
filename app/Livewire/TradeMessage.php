<?php

namespace App\Livewire;

use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class TradeMessage extends Component
{
    public int $receiverId;

    public string $message = '';

    public bool $sent = false;

    protected $rules = [
        'message' => 'required',
    ];

    public function mount(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function send(): void
    {
        $this->validate();

        $receiver = User::findOrFail($this->receiverId);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverId,
            'message' => $this->message,
        ]);

        Mail::send(new UserMessage(auth()->user(), $receiver, $this->message));

        $this->message = '';
        $this->sent = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.trade-message');
    }
}
