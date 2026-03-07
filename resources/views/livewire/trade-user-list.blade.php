<div>
    <div class="space-y-3 mb-6">
        <div class="flex flex-nowrap items-center gap-2">
            <flux:select wire:model.live="country" id="country" class="min-w-[200px] max-w-[280px]">
                <option value="">-- All Traders --</option>
                @foreach($this->countries as $code => $current)
                    <option value="{{ $code }}">{{ $current['name'] ?? '' }}</option>
                @endforeach
            </flux:select>
        </div>
        @if($country && !isset($this->countries[$country]))
            <flux:callout variant="danger">This country is not in our records.</flux:callout>
        @endif
    </div>

    @if($this->users === null)
        <flux:callout variant="info">You don't have any double elePHPant to trade yet.</flux:callout>
    @elseif($this->users->count() === 0)
        <flux:callout variant="info">No users found that can trade with you.</flux:callout>
    @else
        <flux:callout variant="info" icon="information-circle" heading="Found {{ $this->totalTraders }} {{ \Illuminate\Support\Str::plural('user', $this->totalTraders) }} that can trade with you." class="mb-4" />
        <div class="flex justify-end mt-4 mb-4">
            {{ $this->users->links() }}
        </div>
        <div class="space-y-4">
            @foreach($this->users as $user)
                <flux:card class="space-y-0">
                    <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                        @include('trade._user', ['user' => $user, 'countries' => $this->countries])
                    </div>
                    <div class="p-4">
                        @include('trade._possible_deal', ['user' => $user])
                    </div>
                </flux:card>
            @endforeach
        </div>
        <div class="flex justify-end mt-6">
            {{ $this->users->links() }}
        </div>
    @endif
</div>
