<div class="flex flex-nowrap items-center gap-3 mb-6">
    <form action="" method="GET" class="flex flex-nowrap items-center gap-2 flex-1 min-w-0">
        <flux:input type="text" name="q" placeholder="Search for Elephpants" value="{{ request()->input('q') }}" class="min-w-[180px] flex-1 max-w-md" />
        <flux:button type="submit" variant="primary" class="shrink-0">Search</flux:button>
    </form>
    <flux:text class="font-medium shrink-0 whitespace-nowrap ml-2">Species Found: {{ count($elephpants) }}</flux:text>
</div>
