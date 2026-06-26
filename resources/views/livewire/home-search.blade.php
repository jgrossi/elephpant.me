<div class="max-w-6xl mx-auto">
    <div class="flex flex-nowrap items-center gap-3 mb-6">
        <flux:input
            type="text"
            wire:model.live.debounce.300ms="q"
            placeholder="Search for Elephpants"
            class="min-w-[180px] flex-1 max-w-md"
        />
        <flux:text class="font-medium shrink-0 whitespace-nowrap ml-auto">Species Found: {{ count($elephpants) }}</flux:text>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @if($elephpants->count())
            @foreach($elephpants as $elephpant)
                @include('elephpant._single_box', compact('elephpant'))
            @endforeach
        @else
            @include('partials._no_elephpants_found')
        @endif
    </div>
</div>
