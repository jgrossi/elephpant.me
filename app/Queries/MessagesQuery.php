<?php

declare(strict_types=1);

namespace App\Queries;

use App\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class MessagesQuery
{
    public function getMessagesWithLoggedInUserAndSomeoneElse(int $userId): Collection
    {
        $authUserId = auth()->id();

        return $this->getAllQuery()
            ->where(function ($query) use ($authUserId, $userId): void {
                $query->where(function ($q) use ($authUserId, $userId): void {
                    $q->where('m.sender_id', '=', $authUserId)
                        ->orWhere('m.sender_id', '=', $userId);
                })->where(function ($q) use ($authUserId, $userId): void {
                    $q->where('m.receiver_id', '=', $userId)
                        ->orWhere('m.receiver_id', '=', $authUserId);
                });
            })
            ->orderBy('m.id', 'asc')
            ->get();
    }

    private function getAllQuery()
    {
        return Message::query()
            ->select([
                'm.id',
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

    public function getConversations(): Collection
    {
        $authUserId = auth()->id();

        $latestPerOther = Message::query()
            ->selectRaw(
                'CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as other_id, MAX(id) as max_id',
                [$authUserId]
            )
            ->where(function ($q) use ($authUserId): void {
                $q->where('sender_id', $authUserId)
                    ->orWhere('receiver_id', $authUserId);
            })
            ->groupBy('other_id');

        return $this->getAllQuery()
            ->joinSub($latestPerOther, 'latest', function ($join) use ($authUserId): void {
                $join->on(DB::raw(sprintf('CASE WHEN m.sender_id = %s THEN m.receiver_id ELSE m.sender_id END', $authUserId)), '=', 'latest.other_id')
                    ->on('m.id', '=', 'latest.max_id');
            })
            ->orderBy('m.id', 'desc')
            ->get();
    }
}
