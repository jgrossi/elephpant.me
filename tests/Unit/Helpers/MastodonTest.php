<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\Mastodon;
use Tests\TestCase;

class MastodonTest extends TestCase
{
    /**
     * @dataProvider mastodonHandleAndProfileUrlProvider
     */
    public function testItRetrieveMastodonProfileUrlFromHandle(?string $handle, ?string $expectedProfileUrl): void
    {
        $actualProfileUrl = Mastodon::profileUrl($handle);

        $this->assertSame($expectedProfileUrl, $actualProfileUrl);
    }

    public function mastodonHandleAndProfileUrlProvider(): iterable
    {
        yield 'null handle' => [null, null];
        yield 'empty handle' => ['', null];
        yield '@' => ['@', null];

        yield 'absolute secured url' => ['https://arnissolle.com/users/pierre', 'https://arnissolle.com/users/pierre'];
        yield 'absolute unsecured url' => ['http://arnissolle.com/users/pierre', 'http://arnissolle.com/users/pierre'];

        yield 'mastodon.social: arnissolle' => ['arnissolle', 'https://mastodon.social/@arnissolle'];
        yield 'mastodon.social: @arnissolle' => ['@arnissolle', 'https://mastodon.social/@arnissolle'];
        yield 'mastodon.social: @@arnissolle' => ['@@arnissolle', 'https://mastodon.social/@arnissolle'];

        yield 'arnissolle.com: pierre@arnissolle.com' => ['pierre@arnissolle.com', 'https://arnissolle.com/@pierre'];
        yield 'arnissolle.com: @pierre@arnissolle.com' => ['@pierre@arnissolle.com', 'https://arnissolle.com/@pierre'];
    }
}
