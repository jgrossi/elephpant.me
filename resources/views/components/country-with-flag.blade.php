@props(['country' => null])

@if($country)
    <flux:text class="mt-1">
        {{ $country['name'] ?? '' }}
        @if ($flag = $country['flag'] ?? null)
            <span class="ml-1">{!! $flag !!}</span>
        @endif
    </flux:text>
@endif
