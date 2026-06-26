<div id="stats-content" class="grid grid-cols-1 md:grid-cols-3 gap-6" wire:key="herd-stats">
    <flux:card class="p-5 text-center">
        <flux:text variant="subtle" class="text-sm font-medium">Unique</flux:text>
        <flux:heading size="xl" class="mt-2">{{ $unique }}</flux:heading>
    </flux:card>
    <flux:card class="p-5 text-center">
        <flux:text variant="subtle" class="text-sm font-medium">Double</flux:text>
        <flux:heading size="xl" class="mt-2">{{ $double }}</flux:heading>
    </flux:card>
    <flux:card class="p-5 text-center">
        <flux:text variant="subtle" class="text-sm font-medium">Total</flux:text>
        <flux:heading size="xl" class="mt-2">{{ $total }}</flux:heading>
    </flux:card>
</div>
