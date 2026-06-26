@php
    $currentFilter = request('filter');
    $boxes = [
        ['label' => 'Unique', 'value' => $stats['unique'], 'filter' => 'unique'],
        ['label' => 'Double', 'value' => $stats['double'], 'filter' => 'double'],
        ['label' => 'Total', 'value' => $stats['total'], 'filter' => null],
    ];
@endphp
<div id="stats-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6" wire:key="herd-stats-static">
    @foreach($boxes as $box)
        @php
            $isActive = ($box['filter'] === null && empty($currentFilter)) || $currentFilter === $box['filter'];
            $href = $box['filter'] === null ? url()->current() : url()->current().'?filter='.$box['filter'];
        @endphp
        <a href="{{ $href }}"
           class="block rounded-lg border py-4 px-4 text-center transition {{ $isActive
               ? 'border-indigo-500 bg-indigo-50 dark:border-indigo-400 dark:bg-indigo-900/20'
               : 'border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
            <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">{{ $box['label'] }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ $box['value'] }}</flux:heading>
        </a>
    @endforeach
</div>
