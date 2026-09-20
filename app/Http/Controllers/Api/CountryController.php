<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\CountryCollectorsQuery;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'countries' => app(CountryCollectorsQuery::class)->fetchAll(),
        ]);
    }
}
