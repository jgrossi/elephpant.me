@props([
    'user',
    'countries' => [],
    'nameAsLink' => false,
    'compact' => false,
    'lastUpdated' => null,
])

@php
    $country = $countries[$user->country_code] ?? null;
    $avatarClass = $compact ? 'max-sm:!size-8' : '';
    $socialLinks = array_filter([
        'X/Twitter' => $user->x_handle ? [
            'url' => 'https://twitter.com/'.$user->x_handle,
            'icon' => 'x',
        ] : null,
        'Mastodon' => $user->mastodon ? [
            'url' => $user->mastodonUrl(),
            'icon' => 'mastodon',
        ] : null,
        'Bluesky' => $user->bluesky ? [
            'url' => $user->blueskyUrl(),
            'icon' => 'bluesky',
        ] : null,
    ]);
@endphp

<div class="flex flex-wrap items-start gap-4" {{ $attributes }}>
    <x-user-avatar :user="$user" :avatarClass="$avatarClass" />
    <div class="min-w-0">
        @if($nameAsLink && $compact)
            <p class="mb-0 font-medium">
                <a href="{{ route('herds.show', $user->username) }}" wire:navigate class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100">{{ $user->name }}</a>
            </p>
        @elseif($nameAsLink)
            <flux:heading size="xl" level="1" class="text-zinc-600 dark:text-zinc-300">
                <a href="{{ route('herds.show', $user->username) }}" wire:navigate class="hover:text-zinc-900 dark:hover:text-zinc-100">{{ $user->name }}</a>
            </flux:heading>
        @else
            <flux:heading size="xl" level="1" class="text-zinc-600 dark:text-zinc-300">{{ $user->name }}</flux:heading>
        @endif
        <x-country-with-flag :country="$country" />
        @if($lastUpdated)
            <flux:text class="{{ $compact ? 'text-sm text-zinc-500 dark:text-zinc-400' : '' }} mt-1 flex items-center gap-1">
                <flux:icon icon="calendar" variant="outline" class="size-4" />
                Herd last updated {{ \Carbon\Carbon::parse($lastUpdated)->diffForHumans() }}
            </flux:text>
        @endif
        @if($socialLinks)
            <div data-social-links class="mt-1 flex items-center gap-1.5">
                @foreach($socialLinks as $platform => $socialLink)
                    <flux:tooltip :content="$platform" position="top">
                        <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $platform }}" class="flex size-11 items-center justify-center rounded-md text-zinc-700 transition-colors hover:bg-zinc-100 hover:text-zinc-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white dark:focus-visible:outline-blue-400">
                            <flux:icon :name="$socialLink['icon']" class="size-6" />
                        </a>
                    </flux:tooltip>
                @endforeach
            </div>
        @endif
    </div>
</div>
