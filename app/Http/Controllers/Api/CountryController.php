<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\CountryCollectorsQuery;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseField;

#[Group('Countries', 'Which countries have public collectors, and how many.')]
class CountryController extends Controller
{
    #[Endpoint(
        title: 'List countries with public collectors',
        description: <<<'DESC'
            Returns every country with at least one collector, ordered by collector count
            descending then name ascending. Use this to discover which country codes are worth
            passing to /ranking, instead of scraping the ranking page's country selector.

            A collector is a public herd holding at least one elePHPant, the same people
            /ranking lists, so `collectors` matches the total from /ranking?country=<code>.
            Registered accounts that have not added an elePHPant are not counted and their
            country does not appear here.
            DESC,
    )]
    #[ResponseField('countries[].code', 'string', 'ISO 3166-1 alpha-3 country code, as used by the country filter on /ranking.')]
    #[ResponseField('countries[].code_2', 'string', 'ISO 3166-1 alpha-2 country code, handy for flag emoji.')]
    #[ResponseField('countries[].collectors', 'integer', 'Public herds in this country holding at least one elePHPant. Agrees with the total from /ranking?country=<code>.')]
    public function index(): JsonResponse
    {
        return response()->json([
            'countries' => app(CountryCollectorsQuery::class)->fetchAll(),
        ]);
    }
}
