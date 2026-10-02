<?php

use App\User;
use Illuminate\Support\Facades\Blade;

function renderUserProfile(User $user, bool $compact = false): string
{
    return Blade::render(
        '<x-user-profile :user="$user" :compact="$compact" />',
        ['user' => $user, 'compact' => $compact],
    );
}

function profileXPath(string $html): DOMXPath
{
    $document = new DOMDocument();

    $previousErrorMode = libxml_use_internal_errors(true);
    $document->loadHTML($html);
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrorMode);

    return new DOMXPath($document);
}

function socialProfileLinks(DOMXPath $xpath): DOMNodeList
{
    return $xpath->query('//*[@data-social-links]//a');
}

test('configured social accounts render labeled links with their existing URLs', function (): void {
    $user = User::factory()->make([
        'x_handle' => 'kj_dev',
        'mastodon' => '@kj@phpc.social',
        'bluesky' => '@kj.bsky.social',
    ]);
    $xpath = profileXPath(renderUserProfile($user));
    $links = socialProfileLinks($xpath);

    expect($links->length)->toBe(3);

    $expectedLinks = [
        'X/Twitter' => 'https://twitter.com/kj_dev',
        'Mastodon' => 'https://phpc.social/@kj',
        'Bluesky' => 'https://bsky.app/profile/kj.bsky.social',
    ];

    foreach ($expectedLinks as $platform => $url) {
        $platformLinks = $xpath->query('//*[@data-social-links]//a[@aria-label="'.$platform.'"]');

        expect($platformLinks->length)->toBe(1)
            ->and($platformLinks->item(0)->getAttribute('href'))->toBe($url)
            ->and($platformLinks->item(0)->getAttribute('target'))->toBe('_blank')
            ->and($platformLinks->item(0)->getAttribute('rel'))->toBe('noopener noreferrer')
            ->and($platformLinks->item(0)->getAttribute('class'))->toContain('focus-visible:outline-2');
    }

    expect($xpath->query('//*[@data-flux-tooltip]')->length)->toBe(3)
        ->and($xpath->query('//*[@data-social-links]//svg[@aria-hidden="true"]')->length)->toBe(3);
});

test('only configured social account renders when user has a single link', function (): void {
    $user = User::factory()->make([
        'x_handle' => null,
        'mastodon' => '@kj@phpc.social',
        'bluesky' => null,
    ]);
    $xpath = profileXPath(renderUserProfile($user));
    $groups = $xpath->query('//*[@data-social-links]');
    $links = socialProfileLinks($xpath);

    expect($groups->length)->toBe(1)
        ->and($links->length)->toBe(1);

    $mastodonLinks = $xpath->query('//*[@data-social-links]//a[@aria-label="Mastodon"]');

    expect($mastodonLinks->length)->toBe(1)
        ->and($mastodonLinks->item(0)->getAttribute('href'))->toBe('https://phpc.social/@kj')
        ->and($mastodonLinks->item(0)->getAttribute('target'))->toBe('_blank')
        ->and($mastodonLinks->item(0)->getAttribute('rel'))->toBe('noopener noreferrer')
        ->and($xpath->query('//*[@data-social-links]//a[@aria-label="X/Twitter"]')->length)->toBe(0)
        ->and($xpath->query('//*[@data-social-links]//a[@aria-label="Bluesky"]')->length)->toBe(0)
        ->and($xpath->query('//*[@data-flux-tooltip]')->length)->toBe(1)
        ->and($xpath->query('//*[@data-social-links]//svg[@aria-hidden="true"]')->length)->toBe(1);
});

test('social links render as icons in one horizontal group', function (): void {
    $user = User::factory()->make([
        'x_handle' => 'kj_dev',
        'mastodon' => '@kj@phpc.social',
        'bluesky' => '@kj.bsky.social',
    ]);
    $xpath = profileXPath(renderUserProfile($user));
    $groups = $xpath->query('//*[@data-social-links]');

    expect($groups->length)->toBe(1)
        ->and($groups->item(0)->getAttribute('class'))->toContain('flex')
        ->and($groups->item(0)->getAttribute('class'))->toContain('items-center')
        ->and($xpath->query('//*[@data-social-links]//svg')->length)->toBe(3);
});

test('missing social accounts do not render an empty group or links', function (): void {
    $user = User::factory()->make([
        'x_handle' => null,
        'mastodon' => null,
        'bluesky' => null,
    ]);
    $xpath = profileXPath(renderUserProfile($user));

    expect($xpath->query('//*[@data-social-links]')->length)->toBe(0)
        ->and($xpath->query('//a[@aria-label="X/Twitter" or @aria-label="Mastodon" or @aria-label="Bluesky"]')->length)->toBe(0);
});

test('compact and standard profiles both render configured social links', function (): void {
    $user = User::factory()->make([
        'x_handle' => 'kj_dev',
        'mastodon' => '@kj@phpc.social',
        'bluesky' => '@kj.bsky.social',
    ]);

    foreach ([false, true] as $compact) {
        $xpath = profileXPath(renderUserProfile($user, $compact));

        expect(socialProfileLinks($xpath)->length)->toBe(3);
    }
});
