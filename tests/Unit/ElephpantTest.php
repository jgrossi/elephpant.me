<?php

declare(strict_types=1);

use App\Elephpant;
use App\Format;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

test('elephpant has users relation', function (): void {
    $elephpant = new Elephpant();

    expect($elephpant->users())->toBeInstanceOf(BelongsToMany::class);
});

test('scopeFilter searches name, description, sponsor, and year', function (): void {
    $request = new Request(['q' => 'Blue']);

    $query = Elephpant::query()->filter($request);

    expect($query->toSql())->toBe(
        'select * from "elephpants" where ("name" LIKE ? or "description" LIKE ? or "sponsor" LIKE ? or "year" LIKE ?)'
    )->and($query->getBindings())->toBe(['%Blue%', '%Blue%', '%Blue%', '%Blue%']);
});

test('formats a small elePHPant without the format in the name', function (): void {
    $elephpant = new Elephpant();
    $elephpant->name = 'Foo';
    $elephpant->format = Format::Small;

    expect($elephpant->formattedName())->toBe('Foo');
});

test('formats a large elePHPant with the format in the name', function (): void {
    $elephpant = new Elephpant();
    $elephpant->name = 'Bar';
    $elephpant->format = Format::Large;

    expect($elephpant->formattedName())->toBe('Bar (Large)');
});
