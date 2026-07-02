@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 space-y-3">
                <flux:card size="sm">
                    <x-user-profile :user="$otherUser" :countries="$countries" name-as-link />
                </flux:card>
                @if($messages->isEmpty())
                    <flux:callout variant="warning">
                        <flux:callout.text>You don't have any messages with {{ $otherUser->username }} yet.</flux:callout.text>
                    </flux:callout>
                @else
                    @foreach($messages as $message)
                        @php
                        $date = Carbon\Carbon::parse($message->created_at)->format($dateFormat);
                        @endphp
                        @if($message->sender_id == auth()->user()->id)
                            <div class="flex gap-3 items-start justify-end">
                                <div class="flex flex-col min-w-0">
                                    <flux:card class="block border py-3 px-4 border-indigo-500 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-900/20">
                                        {!! nl2br(e($message->message)) !!}
                                    </flux:card>
                                    <time class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" datetime="{{ $message->created_at }}">
                                        {{ $date }}
                                    </time>
                                </div>

                                <div class="flex-none">
                                    <x-user-avatar :user="auth()->user()" />
                                </div>
                            </div>
                        @else
                            <div class="flex gap-3 items-start">
                                <div class="flex-none">
                                    <x-user-avatar :user="$otherUser" />
                                </div>

                                <div class="flex flex-col min-w-0">
                                    <flux:card class="block border py-3 px-4 border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                                        {!! nl2br(e($message->message)) !!}
                                    </flux:card>
                                    <time class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" datetime="{{ $message->created_at }}">
                                        {{ $date }}
                                    </time>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <flux:input type="text">
                        <x-slot name="iconTrailing">
                            <flux:button size="sm" variant="primary" icon="paper-airplane" class="cursor-pointer" />
                            {{--<flux:button type="button" variant="primary" wire:click="send">Send Message</flux:button>--}}
                        </x-slot>
                    </flux:input>
                @endif
            </div>
        </div>
    </div>
@endsection
