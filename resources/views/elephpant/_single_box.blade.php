<flux:card class="h-full flex flex-col p-0" id="elephpant-{{ $elephpant->id }}">
    <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" alt="{{ $elephpant->name }}" class="w-full object-cover rounded-t-lg aspect-square" loading="lazy" decoding="async">
    <div class="p-4 flex-1 flex flex-col">
        <flux:heading size="lg" class="mb-2">{{ $elephpant->name }}</flux:heading>
        <flux:text class="text-sm">
            {{ $elephpant->description }}<br>
            <strong>{{ $elephpant->sponsor }}</strong><br>
            {{ $elephpant->year }}
        </flux:text>
    </div>
</flux:card>
