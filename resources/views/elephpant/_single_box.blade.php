<flux:card class="h-full flex flex-col p-0" id="elephpant-{{ $elephpant->id }}">
    <x-elephpant-image :elephpant="$elephpant" class="w-full object-cover rounded-t-lg aspect-square" />
    <div class="p-4 flex-1 flex flex-col">
        <flux:heading size="lg" class="mb-2">{{ $elephpant->name }}</flux:heading>
        <flux:text class="text-sm">
            {{ $elephpant->description }}<br>
            <strong>{{ $elephpant->sponsor }}</strong><br>
            {{ $elephpant->year }}
        </flux:text>
    </div>
</flux:card>
