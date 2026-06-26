@php
    $hasPivot = isset($elephpant->pivot) && $elephpant->pivot !== null;
@endphp
<flux:tooltip :content="$elephpant->name . ' – ' . $elephpant->description" position="top">
    <div class="flex items-center gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 px-3 py-2 w-fit">
        <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" width="64" height="64" alt="" class="rounded object-cover aspect-square shrink-0" loading="lazy" decoding="async">
        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $elephpant->name }}</span>
        @if($hasPivot && ($elephpant->pivot->quantity ?? 0) > 1)
            <flux:badge size="sm" color="purple">{{ $elephpant->pivot->quantity - 1 }}</flux:badge>
        @endif
    </div>
</flux:tooltip>
