<div class="space-y-3">
    <div class="flex flex-nowrap items-center gap-2">
        <div class="w-fit">
            <flux:select wire:model.live="country" id="country" class="min-w-[200px] max-w-[280px]">
            <option value="">-- Global Ranking --</option>
            @foreach($countries as $code => $current)
                <option value="{{ $code }}">{{ $current['name'] ?? '' }}</option>
            @endforeach
        </flux:select>
        </div>
    </div>

    @if($country && !isset($countries[$country]))
        <flux:callout variant="danger">Ops! This country is not in our records yet.</flux:callout>
    @endif

    <flux:card>
        <div class="p-0">
            @if($users->count())
                <flux:table class="ranking-table">
                    <flux:table.columns>
                        <flux:table.column width="7%" align="center">Rank</flux:table.column>
                        <flux:table.column width="30%">Name</flux:table.column>
                        <flux:table.column>Country</flux:table.column>
                        <flux:table.column width="10%" align="center">Unique</flux:table.column>
                        <flux:table.column width="10%" align="center">Total</flux:table.column>
                        <flux:table.column width="12%" align="center">Updated</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach($users as $key => $user)
                            @php $userCountry = $countries[$user->country_code] ?? null; @endphp
                            <flux:table.row>
                                <flux:table.cell class="text-center">{{ $key + 1 }}</flux:table.cell>
                                <flux:table.cell>
                                    <a href="{{ route('herds.show', $user->username) }}" wire:navigate class="underline hover:no-underline">{{ $user->name }}</a>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if($userCountry && ($userCountry['flag'] ?? null))
                                        <span class="mr-1">{!! $userCountry['flag'] !!}</span>
                                    @endif
                                    {{ $userCountry['name'] ?? 'N/A' }}
                                </flux:table.cell>
                                <flux:table.cell class="text-center">{{ $user->elephpants_unique }}</flux:table.cell>
                                <flux:table.cell class="text-center">{{ $user->elephpants_total }}</flux:table.cell>
                                <flux:table.cell class="text-center">{{ $user->last_update->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @else
                <flux:callout variant="info" class="m-4 mb-0">No users registered yet.</flux:callout>
            @endif
        </div>
    </flux:card>
</div>
