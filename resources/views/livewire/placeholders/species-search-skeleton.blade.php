<div class="w-full max-w-6xl mx-auto">
    <div class="flex flex-nowrap items-center gap-3 mb-6">
        <flux:skeleton class="h-10 flex-1 max-w-md rounded-lg" animate="shimmer" />
        <flux:text class="font-medium shrink-0 whitespace-nowrap ml-auto text-zinc-500 dark:text-zinc-400">Species Found: …</flux:text>
    </div>

    <flux:skeleton.group animate="shimmer">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach(range(1, 12) as $i)
                <flux:card class="h-full flex flex-col p-0 overflow-hidden">
                    <flux:skeleton class="w-full aspect-square rounded-t-lg rounded-b-none" />
                    <div class="p-4 flex-1 flex flex-col gap-2">
                        <flux:skeleton.line class="w-3/4" />
                        <flux:skeleton.line class="w-full" />
                        <flux:skeleton.line class="w-1/2" />
                    </div>
                </flux:card>
            @endforeach
        </div>
    </flux:skeleton.group>
</div>
