<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                  $id
 * @property int                  $sender_id
 * @property int                  $receiver_id
 * @property string               $message
 * @property \Carbon\Carbon       $created_at
 * @property-read User            $sender
 * @property-read User            $receiver
 * @property int|null             $message_count
 * @property string|null          $conversation_started_at
 * @property string|null          $last_message_at
 */
class Message extends Model
{
    #[\Override]
    protected $fillable = ['sender_id', 'receiver_id', 'message'];

    /** @return BelongsTo<User, $this> */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** @return BelongsTo<User, $this> */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
