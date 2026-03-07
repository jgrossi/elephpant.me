<div>
    @if($sent)
        <flux:callout variant="success" icon="check-circle" heading="The message was sent to the user." class="mb-0" />
    @else
        <div class="space-y-3">
            <flux:field>
                <flux:textarea wire:model="message" rows="2" placeholder="Hey, just saw you're looking for an elePHPant I have double. Let's trade?" />
                <flux:error name="message" />
            </flux:field>
            <flux:button type="button" variant="primary" wire:click="send">Send Message</flux:button>
        </div>
    @endif
</div>
