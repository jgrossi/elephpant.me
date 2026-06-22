<flux:card class="h-full flex flex-col p-0" id="elephpant-{{ $elephpant->id }}">
    <x-elephpant-image :elephpant="$elephpant" class="w-full object-cover rounded-t-lg aspect-square" />
    <div class="p-4 flex-1 flex flex-col">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <flux:heading size="lg" class="mb-0">{{ $elephpant->name }}</flux:heading>
            @if (isset($tradePossibilites[$elephpant->id]) && ($tradePossibilites[$elephpant->id]['count'] ?? 0) > 0)
                @php $trade = $tradePossibilites[$elephpant->id]; @endphp
                @if ($trade['type'] == 'senders')
                    <flux:link href="{{ route('trades.senders', $elephpant->id) }}" wire:navigate class="inline-block [&_.flux-badge]:cursor-pointer">
                        <flux:badge color="green" size="sm" class="!text-xs">{{ $trade['count'] }} in trade area</flux:badge>
                    </flux:link>
                @else
                    <flux:link href="{{ route('trades.receivers', $elephpant->id) }}" wire:navigate class="inline-block [&_.flux-badge]:cursor-pointer">
                        <flux:badge color="green" size="sm" class="!text-xs">{{ $trade['count'] }} in trade area</flux:badge>
                    </flux:link>
                @endif
            @else
                <flux:badge variant="outline" size="sm" class="!text-xs text-zinc-500 dark:text-zinc-400 opacity-60">0 in trade area</flux:badge>
            @endif
        </div>
        <flux:text class="text-sm mb-4">
            {{ $elephpant->description }}<br>
            <strong>{{ $elephpant->sponsor }}</strong><br>
            {{ $elephpant->year }}
        </flux:text>
        <div class="mt-auto pt-3 border-t border-zinc-200 dark:border-zinc-700 w-full min-w-0">
            <div class="herd-counter flex items-center gap-1 w-full min-w-0" wire:key="counter-{{ $elephpant->id }}">
                <flux:button type="button" variant="outline" size="sm" icon="minus" wire:click="decrementQuantity({{ $elephpant->id }})" class="shrink-0" />
                <flux:input
                    type="number"
                    class="min-w-0 flex-1 text-center"
                    placeholder="0"
                    wire:model.live.500ms="userElephpants.{{ $elephpant->id }}"
                    min="0"
                />
                <flux:button type="button" variant="outline" size="sm" icon="plus" wire:click="incrementQuantity({{ $elephpant->id }})" class="shrink-0" />
            </div>
        </div>
    </div>
</flux:card>
