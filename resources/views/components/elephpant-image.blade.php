@props([
    'elephpant',
    'loading' => 'lazy',
    'fetchpriority' => null,
])

<img
    src="{{ asset('storage/elephpants/' . $elephpant->image) }}"
    alt="{{ $elephpant->name }}"
    width="300"
    height="300"
    loading="{{ $loading }}"
    decoding="async"
    @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
    {{ $attributes }}
>
