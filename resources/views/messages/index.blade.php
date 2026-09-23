@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
        <div>
            <flux:heading size="xl" level="1">Messages</flux:heading>
            <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Browse your conversations with other collectors.</flux:text>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if($messages->isEmpty())
                    <div class="alert alert-info">
                        You don't have any messages yet.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($messages as $message)
                            @php
                                $otherUser = $message->sender_id === auth()->id()
                                    ? $message->receiver
                                    : $message->sender;

                                $preview = Str::limit($message->message, 80);
                                $messageCount = (int) $message->message_count;
                                $startedAt = \Carbon\Carbon::parse($message->conversation_started_at);
                                $lastMessageAt = \Carbon\Carbon::parse($message->last_message_at);
                            @endphp

                            <a href="{{ route('messages.conversation', $otherUser->username) }}"
                                class="flex flex-row items-center gap-x-4 rounded-lg border py-4 px-4 transition border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">

                                <flux:avatar
                                    circle
                                    size="lg"
                                    name="{{ $otherUser->name }}"
                                    color="auto"
                                    :color:seed="$otherUser->id"
                                    :src="$otherUser->localAvatarUrl()"
                                    alt="{{ $otherUser->name }}"
                                />

                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $otherUser->name }}
                                    </div>

                                    <div class="text-sm text-zinc-600 dark:text-zinc-400 truncate">
                                        {{ $preview }}
                                    </div>

                                    <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $messageCount }} {{ Str::plural('message', $messageCount) }}
                                    </div>
                                </div>

                                <div class="ml-auto text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap pl-4 text-right space-y-1">
                                    <div>Started: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $startedAt->format($dateFormat) }}</span></div>
                                    <div>Last message: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $lastMessageAt->format($dateFormat) }}</span></div>
                                </div>

                            </a>

                        @endforeach

                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection
