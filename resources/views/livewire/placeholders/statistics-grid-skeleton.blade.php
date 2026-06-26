<div class="w-full">
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
