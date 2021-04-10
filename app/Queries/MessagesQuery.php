<?php

declare(strict_types=1);

namespace App\Queries;

use App\Message;
use Illuminate\Database\Eloquent\Collection;

final class MessagesQuery
{
    public function getMessagesWithLoggedInUser(): Collection
    {
        $authUserId = auth()->id();

        return $this->getAllQuery()
                    ->where(function ($query) use ($authUserId) {
                        $query->where('m.sender_id', '=', $authUserId)
                            ->orWhere('m.receiver_id', '=', $authUserId);
                    })
                    ->orderBy('m.created_at', 'desc')
                    ->get();
    }

    public function getMessagesWithLoggedInUserAndSomeoneElse(int $userId): Collection
    {
        $authUserId = auth()->id();

        return $this->getAllQuery()
                    ->where(function ($query) use ($authUserId, $userId) {
                        $query->where('m.sender_id', '=', $authUserId)
                            ->orWhere('m.sender_id', '=', $userId);
                    })
                    ->where(function ($query) use ($authUserId, $userId) {
                        $query->where('m.receiver_id', '=', $userId)
                            ->orWhere('m.receiver_id', '=', $authUserId);
                    })
                    ->orderBy('m.created_at', 'asc')
                    ->get();
    }

    public function getAllQuery()
    {
        return Message::query()
            ->select([
                'm.message',
                'm.created_at',
                'sender.id as sender_id',
                'receiver.id as receiver_id',
            ])
            ->with('sender')
            ->with('receiver')
            ->from('messages as m')
            ->join('users as sender', 'm.sender_id', '=', 'sender.id')
            ->join('users as receiver', 'm.receiver_id', '=', 'receiver.id');
    }

    public function fetchAll(): Collection
    {
        return $this->getAllQuery()
            ->get();
    }
}
