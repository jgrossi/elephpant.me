<div id="stats-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6" wire:key="herd-stats-static">
    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-4 text-center">
        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Unique</flux:text>
        <flux:heading size="xl" class="mt-1">{{ $stats['unique'] }}</flux:heading>
    </div>
    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-4 text-center">
        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Double</flux:text>
        <flux:heading size="xl" class="mt-1">{{ $stats['double'] }}</flux:heading>
    </div>
    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-4 text-center">
        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Total</flux:text>
        <flux:heading size="xl" class="mt-1">{{ $stats['total'] }}</flux:heading>
    </div>
</div>
