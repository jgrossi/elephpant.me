<div>
    <div class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 py-6 px-6 sm:px-10 md:py-8 md:px-16">
        @if($messages->isEmpty())
            <flux:callout variant="warning">
                <flux:callout.text>You don't have any messages with {{ $otherUser->username }} yet.</flux:callout.text>
            </flux:callout>
        @else
            @php
                $groups = [];

                foreach ($messages as $message) {
                    $lastIndex = count($groups) - 1;
                    $startNewGroup = true;

                    if ($lastIndex >= 0 && $groups[$lastIndex]['sender_id'] === $message->sender_id) {
                        $previousMessage = $groups[$lastIndex]['messages'][array_key_last($groups[$lastIndex]['messages'])];
                        $previousMinute = \Carbon\Carbon::parse($previousMessage->created_at)->format('Y-m-d H:i');
                        $currentMinute = \Carbon\Carbon::parse($message->created_at)->format('Y-m-d H:i');

                        $startNewGroup = $previousMinute !== $currentMinute;
                    }

                    if ($startNewGroup) {
                        $groups[] = [
                            'sender_id' => $message->sender_id,
                            'messages'  => [$message],
                        ];
                    } else {
                        $groups[$lastIndex]['messages'][] = $message;
                    }
                }
            @endphp

            <div class="space-y-12">
                @foreach($groups as $group)
                    @php
                        $isOwn = $group['sender_id'] == auth()->id();
                        $sender = $isOwn ? auth()->user() : $otherUser;
                        $firstMessage = $group['messages'][0];
                        $date = Carbon\Carbon::parse($firstMessage->created_at)->format($dateFormat);
                    @endphp

                    @if($isOwn)
                        <div class="flex gap-3 items-start justify-end">
                            <div class="flex flex-col items-end min-w-0 max-w-md sm:max-w-lg space-y-2">
                                <div class="flex w-full items-baseline justify-end gap-2 text-right">
                                    <span class="font-semibold text-xs text-zinc-800 dark:text-zinc-200">{{ $sender->name }}</span>
                                    <time class="text-xs text-zinc-500 dark:text-zinc-400" datetime="{{ $firstMessage->created_at }}">{{ $date }}</time>
                                </div>
                                @foreach($group['messages'] as $message)
                                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 py-4 px-6 text-sm text-zinc-700 dark:text-zinc-300">
                                        {!! nl2br(e($message->message)) !!}
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex-none">
                                <x-user-avatar :user="$sender" size="sm" />
                            </div>
                        </div>
                    @else
                        <div class="flex gap-3 items-start">
                            <div class="flex-none">
                                <x-user-avatar :user="$sender" size="sm" />
                            </div>
                            <div class="flex flex-col min-w-0 max-w-md sm:max-w-lg space-y-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="font-semibold text-xs text-zinc-800 dark:text-zinc-200">{{ $sender->name }}</span>
                                    <time class="text-xs text-zinc-500 dark:text-zinc-400" datetime="{{ $firstMessage->created_at }}">{{ $date }}</time>
                                </div>
                                @foreach($group['messages'] as $message)
                                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 py-4 px-6 text-sm text-zinc-700 dark:text-zinc-300">
                                        {!! nl2br(e($message->message)) !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div @class(['mt-8' => $messages->isNotEmpty(), 'mt-6' => $messages->isEmpty()])>
            <flux:textarea
                wire:model.live="body"
                rows="4"
                resize="vertical"
                placeholder="Write a message..."
            />
            <flux:error name="body" />

            <div class="mt-3 flex justify-end">
                <flux:button
                    type="button"
                    variant="primary"
                    wire:click="send"
                    class="cursor-pointer"
                    :disabled="blank($body)"
                >
                    Send Message
                </flux:button>
            </div>
        </div>
    </div>
</div>
