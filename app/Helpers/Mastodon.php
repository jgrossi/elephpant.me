<?php

declare(strict_types=1);

namespace App\Helpers;

class Mastodon
{
    public static function profileUrl(?string $handle): ?string
    {
        if (null === $handle) {
            return null;
        }

        if (1 === preg_match('/^https?:\/\//', $handle)) {
            return $handle;
        }

        $handle = trim($handle, '@');

        if (empty($handle)) {
            return null;
        }

        if (1 === preg_match('/^([^@]+)@([^@]+)$/', $handle, $matches)) {
            return "https://{$matches[2]}/@{$matches[1]}";
        }

        return "https://mastodon.social/@{$handle}";
    }
}
