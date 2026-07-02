@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 py-6 md:py-8 mb-6 md:mb-8">
        <div>
            <flux:heading size="xl" level="1">Messages</flux:heading>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if(!$messages)
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
                            @endphp

                            <a href="{{ route('messages.conversation', $otherUser->username) }}"
                                class="flex flex-row items-center gap-x-4 rounded-lg border py-4 px-4 transition border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">

                                <img src="{{ $otherUser->avatar() }}"
                                    class="w-12 h-12 rounded-full object-cover border border-zinc-300 dark:border-zinc-600"
                                    alt="{{ $otherUser->name }}">

                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $otherUser->name }}
                                    </div>

                                    <div class="text-sm text-zinc-600 dark:text-zinc-400 truncate">
                                        {{ $preview }}
                                    </div>
                                </div>

                                <div class="ml-auto text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap pl-4">
                                    {{ $message->created_at->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}
                                </div>

                            </a>

                        @endforeach

                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection
