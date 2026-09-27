<?php

namespace App\Http\Controllers;

use App\Country;
use App\Queries\MessagesQuery;
use App\Queries\TradingUsersQuery;
use App\Queries\TradingUsersQueryOption;
use App\User;

class TradeController extends Controller
{
    /**
     * @queryParam search string Optional substring of a trader's name or username. Example: Ada
     * @queryParam country string Optional ISO 3166-1 alpha-3 country code. Example: GBR
     */
    public function index(TradingUsersQuery $query): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $countryCodesWithTraders = $query->getCountryCodesWithTraders($loggedUser);
        $country = request('country');
        $search = trim((string) request('search', ''));
        if ($country && !in_array($country, $countryCodesWithTraders, true)) {
            $countryCodesWithTraders[] = $country;
        }

        $countries = Country::forDropdown($countryCodesWithTraders);
        $trades = $query->fetchAll($loggedUser, null, 5, $country, false, $search);

        return view('trade.index', [
            'trades'          => $trades,
            'users'           => $trades,
            'search'          => $search,
            'country'         => $country,
            'countries'       => $countries,
            'useLivewireList' => false,
        ]);
    }

    public function senders(int $elephpantId, TradingUsersQuery $query, MessagesQuery $mQuery): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllOnlyIfHeHasElephpant($loggedUser, $elephpantId);
        $country = request('country');

        $options = new TradingUsersQueryOption();
        $options->withSpareElephpantId = $elephpantId;

        $countryCodesWithTraders = $users !== null
            ? $query->getCountryCodesWithTraders($loggedUser, $options)
            : [];
        $currentCountry = request('country');
        if ($currentCountry && !in_array($currentCountry, $countryCodesWithTraders, true)) {
            $countryCodesWithTraders[] = $currentCountry;
        }

        $countries = Country::forDropdown($countryCodesWithTraders);

        return view('trade.index', ['users' => $users, 'country' => $country, 'countries' => $countries, 'useLivewireList' => false]);
    }

    public function receivers(int $elephpantId, TradingUsersQuery $query, MessagesQuery $mQuery): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllWhoLackElephpant($loggedUser, $elephpantId);
        $country = request('country');

        $options = new TradingUsersQueryOption();
        $options->lackElephpantId = $elephpantId;

        $countryCodesWithTraders = $users !== null
            ? $query->getCountryCodesWithTraders($loggedUser, $options)
            : [];
        $currentCountry = request('country');
        if ($currentCountry && !in_array($currentCountry, $countryCodesWithTraders, true)) {
            $countryCodesWithTraders[] = $currentCountry;
        }

        $countries = Country::forDropdown($countryCodesWithTraders);

        return view('trade.index', ['users' => $users, 'country' => $country, 'countries' => $countries, 'useLivewireList' => false]);
    }
}
