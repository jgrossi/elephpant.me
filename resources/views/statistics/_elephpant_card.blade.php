<flux:card class="h-full flex flex-col p-0" id="elephpant-{{ $elephpant->id }}">
    <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" alt="{{ $elephpant->name }}" class="w-full object-cover rounded-t-lg aspect-square" loading="lazy" decoding="async">
    <div class="p-4 flex-1 flex flex-col">
        <flux:heading size="lg" class="mb-2">{{ $elephpant->name }}</flux:heading>
        <flux:text class="text-sm mb-4">
            {{ $elephpant->description }}<br>
            @if(!empty($elephpant->sponsor))
                <strong>{{ $elephpant->sponsor }}</strong><br>
            @endif
            @if(isset($elephpant->year))
                {{ $elephpant->year }}
            @endif
        </flux:text>
        <div class="mt-auto pt-3 border-t border-zinc-200 dark:border-zinc-700 space-y-1">
            <div class="flex justify-between text-sm text-zinc-600 dark:text-zinc-400">
                <span>Ownership</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ $ownershipPercent }}%</span>
            </div>
            <div class="flex justify-between text-sm text-zinc-600 dark:text-zinc-400">
                <span>Users</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ $elephpant->nbElephpant }}</span>
            </div>
            <div class="flex justify-between text-sm text-zinc-600 dark:text-zinc-400">
                <span>Total</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ (int) $elephpant->totalElephpant }}</span>
            </div>
        </div>
        @if(auth()->check() && isset($currentUserElephpants))
            <div class="mt-2">
                @if($currentUserElephpants->contains($elephpant->id))
                    <flux:badge color="green" size="sm">In your collection</flux:badge>
                @else
                    <flux:badge color="red" size="sm">Not in your collection</flux:badge>
                @endif
            </div>
        @endif
    </div>
</flux:card>
