@props([
    'user',
    'countries' => [],
    'nameAsLink' => false,
    'compact' => false,
])

@php
    $country = $countries[$user->country_code] ?? null;
    $avatarClass = $compact ? 'max-sm:!size-8' : '';
@endphp

<div class="flex flex-wrap items-start gap-4" {{ $attributes }}>
    @if($user->hasAvatarImage())
        <flux:avatar size="lg" circle class="{{ $avatarClass }}" src="{{ $user->avatar() }}" alt="{{ $user->name }}" />
    @else
        <flux:avatar size="lg" circle class="{{ $avatarClass }}" name="{{ $user->name }}" color="auto" :color:seed="$user->id" />
    @endif
    <div class="min-w-0">
        @if($nameAsLink)
            <p class="mb-0 font-medium">
                <a href="{{ route('herds.show', $user->username) }}" wire:navigate class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100">{{ $user->name }}</a>
            </p>
        @else
            <flux:heading size="xl" level="1" class="text-zinc-600 dark:text-zinc-300">{{ $user->name }}</flux:heading>
        @endif
        <x-country-with-flag :country="$country" />
        @if($user->twitter)
            <flux:text class="{{ $compact ? 'text-sm text-zinc-500 dark:text-zinc-400' : '' }} mt-1">
                Twitter: <a href="https://twitter.com/{{ $user->twitter }}" target="_blank" rel="noopener noreferrer" class="underline hover:no-underline">{{ '@' . $user->twitter }}</a>
            </flux:text>
        @endif
        @if($user->mastodon)
            <flux:text class="{{ $compact ? 'text-sm text-zinc-500 dark:text-zinc-400' : '' }} mt-1 {{ $compact ? 'block' : '' }}">
                Mastodon: <a href="https://mastodon.social/{{ $user->mastodon }}" target="_blank" rel="noopener noreferrer" class="underline hover:no-underline">{{ $user->mastodon }}</a>
            </flux:text>
        @endif
    </div>
</div>
