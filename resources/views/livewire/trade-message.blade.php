<div>
    @if($sent)
        <flux:callout variant="success" icon="check-circle" heading="The message was sent to the user." class="mb-0" />
    @else
        <div class="space-y-3">
            @php
                $message = $receiverUser->getLastMessageWith(auth()->user());
            @endphp
            @if ($message)
                @php
                    $otherUser = $message->sender_id === auth()->id()
                        ? $message->receiver
                        : $message->sender;

                    $preview = Str::limit($message->message, 80);
                @endphp
                <flux:callout icon="envelope" variant="secondary" heading="Last message" inline>
                    <flux:callout.text>
                        <div>{{ $preview }}</div>
                        <time class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" datetime="">
                            {{ $message->created_at->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }} ago
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
                <flux:textarea wire:model="message" rows="auto" />
                <flux:error name="message" />
            </flux:field>
            <flux:button type="button" variant="primary" wire:click="send">Send Message</flux:button>
        </div>
    @endif
</div>
