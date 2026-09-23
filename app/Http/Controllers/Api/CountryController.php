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
            Returns every country that has at least one public herd, with its alpha-3 code, name
            and collector count, ordered by collector count descending then name ascending. Use
            this to discover which country codes are worth passing to /ranking or
            /herd/{username} lookups, instead of scraping the ranking page's country selector.
            DESC,
    )]
    #[ResponseField('countries[].code', 'string', 'ISO 3166-1 alpha-3 country code.')]
    #[ResponseField('countries[].collectors', 'integer', 'Number of public herds registered in this country.')]
    public function index(): JsonResponse
    {
        return response()->json([
            'countries' => app(CountryCollectorsQuery::class)->fetchAll(),
        ]);
    }
}
