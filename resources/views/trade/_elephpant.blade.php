<li class="flex flex-wrap items-center gap-4 p-3 border-b border-zinc-200 dark:border-zinc-700 last:border-0">
    <div class="flex-1 min-w-0 flex items-center gap-3">
        <flux:tooltip :content="$elephpant->name . ' – ' . $elephpant->description" position="left">
            <a href="#" class="block shrink-0" onclick="return false;">
                <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" width="50" height="50" alt="" class="rounded border border-zinc-200 dark:border-zinc-700 object-cover" loading="lazy" decoding="async">
            </a>
        </flux:tooltip>
        <div>
            <p class="font-medium text-zinc-900 dark:text-white">{{ $elephpant->name }}</p>
            <p class="text-sm text-zinc-500 dark:text-zinc-400"><em>({{ $elephpant->description }})</em></p>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">By {{ $elephpant->sponsor }}</p>
        </div>
    </div>
    <flux:badge size="lg">{{ $elephpant->pivot->quantity - 1 }}</flux:badge>
</li>
