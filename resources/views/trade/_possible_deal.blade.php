<div class="space-y-6 mb-6">
    <div>
        <flux:heading size="sm" class="text-zinc-600 dark:text-zinc-400 mb-2">Their doubles you don't have</flux:heading>
        @if($user->elephpantsToTrade->isEmpty())
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">None</flux:text>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($user->elephpantsToTrade as $elephpant)
                    @include('trade._elephpant_tile', ['elephpant' => $elephpant])
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <flux:heading size="sm" class="text-zinc-600 dark:text-zinc-400 mb-2">They're looking for (you have double)</flux:heading>
        @if($user->elephpantsInterested->isEmpty())
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">None</flux:text>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($user->elephpantsInterested as $elephpant)
                    @include('trade._elephpant_tile', ['elephpant' => $elephpant])
                @endforeach
            </div>
        @endif
    </div>

    <div>
        @livewire('trade-message', ['receiverId' => $user->id], key('trade-message-'.$user->id))
    </div>
</div>
