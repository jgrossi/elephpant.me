<div class="herd-counter flex items-center gap-1 w-full min-w-0" wire:key="counter-{{ $elephpantId }}">
    <flux:button type="button" variant="outline" size="sm" icon="minus" wire:click="decrement" class="shrink-0" />
    <flux:input type="number" class="min-w-0 flex-1 text-center" placeholder="0" wire:model.live.500ms="quantity" min="0" />
    <flux:button type="button" variant="outline" size="sm" icon="plus" wire:click="increment" class="shrink-0" />
</div>
