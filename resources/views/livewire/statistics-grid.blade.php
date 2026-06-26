<div class="w-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($elephpants as $elephpant)
            @php
                $ownershipPercent = $nbUsersWithElephpant > 0
                    ? round((($elephpant->nbElephpant / $nbUsersWithElephpant) * 100), 2)
                    : 0;
            @endphp
            @include('statistics._elephpant_card', [
                'elephpant' => $elephpant,
                'ownershipPercent' => $ownershipPercent,
                'currentUserElephpants' => $currentUserElephpants,
            ])
        @endforeach
    </div>

    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mt-6">
        Ownership = % of users (with at least one elePHPant) who have this species.
    </flux:text>
    @if(auth()->check())
        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block mt-1">
            <flux:badge color="green" size="sm" class="align-middle">In your collection</flux:badge>
            <flux:badge color="red" size="sm" class="align-middle ml-1">Not in your collection</flux:badge>
        </flux:text>
    @endif
</div>
