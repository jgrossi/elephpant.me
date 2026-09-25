<div>
    @if($sent)
        <flux:callout variant="success" icon="check-circle" heading="The message was sent to the user." class="mb-0" />
    @else
        <div class="space-y-3">
            @php
                $lastMessage = $receiverUser->getLastMessageWith(auth()->user());
            @endphp
            @if ($lastMessage)
                @php
                    $otherUser = $lastMessage->sender_id === auth()->id()
                        ? $lastMessage->receiver
                        : $lastMessage->sender;

                    $preview = Str::limit($lastMessage->message, 80);
                @endphp
                <flux:callout icon="envelope" variant="secondary" heading="Last message" inline class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700">
                    <flux:callout.text>
                        <div>{{ $preview }}</div>
                        <time class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" datetime="">
                            {{ $lastMessage->created_at->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }} ago
                        </time>
                    </flux:callout.text>

                    <x-slot name="actions">
                        <flux:button href="{{ route('messages.conversation', $receiverUser->username) }}" icon:trailing="arrow-right">
                            View conversation
                        </flux:button>
                    </x-slot>
                </flux:callout>
            @endif

            <flux:field>
                <flux:textarea wire:model.live="message" rows="4" resize="vertical" />
                <flux:error name="message" />
            </flux:field>
            <div class="flex justify-end">
                <flux:button
                    type="button"
                    variant="primary"
                    wire:click="send"
                    class="cursor-pointer"
                    :disabled="blank($message)"
                >
                    Send Message
                </flux:button>
            </div>
        </div>
    @endif
</div>
