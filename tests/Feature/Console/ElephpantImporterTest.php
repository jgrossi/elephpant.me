<?php

declare(strict_types=1);

use App\Console\Services\ElephpantImporter;
use App\Elephpant;
use App\Format;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\NullOutput;

test('elephpant import service creates or updates elephpants from json without image', function (): void {
    $minimal = [
        (object)['id' => 9999, 'format' => 'small', 'name' => 'Test Elephant', 'description' => 'Desc', 'sponsor' => 'Sponsor', 'year' => 2020, 'color' => 'transparent'],
    ];

    $importer = new ElephpantImporter($minimal, new NullOutput());
    $importer->import();

    $elephpant = Elephpant::find(9999);
    expect($elephpant)->not->toBeNull()
        ->and($elephpant->format)->toEqual(Format::Small)
        ->and($elephpant->name)->toBe('Test Elephant')
        ->and($elephpant->year)->toBe(2020)
        ->and($elephpant->color)->toBe('transparent')
        ->and($elephpant->image)->toBeNull();
});

test('elephpant import service processes image when present', function (): void {
    $generatedImagePath = storage_path('app/public/elephpants/9998-with-image.jpg');

    $minimal = [
        (object)['id' => 9998, 'format' => 'large', 'name' => 'With Image', 'description' => 'Desc', 'sponsor' => 'Sponsor', 'year' => 2021, 'color' => 'blue', 'image' => '1-original-blue.jpg'],
    ];

    $importer = new ElephpantImporter($minimal, new NullOutput());
    $importer->import();

    $elephpant = Elephpant::find(9998);
    expect($elephpant)->not->toBeNull()
        ->and($elephpant->name)->toBe('With Image')
        ->and($elephpant->format)->toBe(Format::Large)
        ->and($elephpant->image)->toBe('9998-with-image.jpg')
        ->and(File::exists($generatedImagePath))->toBeTrue();

    File::delete($generatedImagePath);
});

test('elephpant import service fails when an invalid enum is used', function (): void {
    $minimal = [
        (object)['id' => 9999, 'format' => 'invalid', 'name' => 'Invalid', 'description' => 'Invalid', 'sponsor' => 'Invalid', 'year' => 2020, 'color' => 'transparent'],
    ];

    $importer = new ElephpantImporter($minimal, new NullOutput());

    $this->expectException(ValueError::class);
    $importer->import();
});

test('elephpant import service fails when a property is empty', function (): void {
    $minimal = [
        (object)['id' => 9997, 'format' => 'small', 'name' => 'Empty Sponsor', 'description' => 'Desc', 'sponsor' => '', 'year' => 2020, 'color' => 'blue'],
    ];

    $importer = new ElephpantImporter($minimal, new NullOutput());

    expect(fn () => $importer->import())->toThrow(LogicException::class, 'Property sponsor must not be empty on 9997');
    expect(Elephpant::find(9997))->toBeNull();
});
