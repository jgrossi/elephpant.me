<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

function collector(string $country, int $elephpants = 1, bool $public = true): User
{
    $user = User::factory()->create(['is_public' => $public, 'country_code' => $country]);

    Elephpant::factory()->count($elephpants)->create()->each(
        fn (Elephpant $elephpant) => $user->elephpants()->attach($elephpant->id, ['quantity' => 1])
    );

    return $user;
}

test('api countries endpoint returns codes, names and collector counts ordered by collectors desc', function (): void {
    collector('DNK');
    collector('DNK');
    collector('GBR');

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonCount(2, 'countries');
    $response->assertJsonPath('countries.0.code', 'DNK');
    $response->assertJsonPath('countries.0.code_2', 'DK');
    $response->assertJsonPath('countries.0.name', 'Denmark');
    $response->assertJsonPath('countries.0.collectors', 2);
    $response->assertJsonPath('countries.1.code', 'GBR');
    $response->assertJsonPath('countries.1.code_2', 'GB');
    $response->assertJsonPath('countries.1.collectors', 1);
});

test('api countries endpoint excludes private herds from the count', function (): void {
    collector('DNK');
    collector('DNK', public: false);

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonPath('countries.0.collectors', 1);
});

test('api countries endpoint ignores registered accounts with an empty herd', function (): void {
    collector('DNK');
    User::factory()->count(3)->create(['is_public' => true, 'country_code' => 'DNK']);
    User::factory()->count(5)->create(['is_public' => true, 'country_code' => 'ALA']);

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonCount(1, 'countries');
    $response->assertJsonPath('countries.0.code', 'DNK');
    $response->assertJsonPath('countries.0.collectors', 1);
});

test('api countries endpoint counts a collector once however many elephpants they hold', function (): void {
    collector('DNK', elephpants: 7);

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonPath('countries.0.collectors', 1);
});

test('api countries collector counts agree with the ranking endpoint', function (): void {
    collector('DNK', elephpants: 3);
    collector('DNK');
    collector('GBR', elephpants: 2);
    collector('GBR', public: false);
    User::factory()->count(4)->create(['is_public' => true, 'country_code' => 'FRA']);

    $countries = collect($this->getJson(route('api.countries.index'))->json('countries'));

    expect($countries->sum('collectors'))
        ->toBe($this->getJson(route('api.ranking.index'))->json('meta.total'));

    $countries->each(function (array $country): void {
        $ranked = $this->getJson(route('api.ranking.index', ['country' => $country['code']]))
            ->json('meta.total');

        expect($country['collectors'])->toBe($ranked, "collectors disagree for {$country['code']}");
    });
});

test('api countries endpoint returns an empty list when there are no public collectors', function (): void {
    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonCount(0, 'countries');
});
