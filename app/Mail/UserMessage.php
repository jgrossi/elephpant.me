<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMessage extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\User
     */
    public $sender;

    /**
     * @var \App\User
     */
    public $receiver;

    /**
     * @var string
     */
    public $message;

    public function __construct(User $sender, User $receiver, string $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
    }

    public function build()
    {
        return $this->markdown('emails.message')
            ->subject('New trade request')
            ->to($this->receiver->email)
            ->replyTo($this->sender->email);
    }
}
