<?php

declare(strict_types=1);

use App\Elephpant;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

test('elephpants read command creates or updates elephpants from json without image', function (): void {
    $jsonPath = resource_path('data/elephpants.json');
    $backup = $jsonPath.'.bak';
    if (! is_file($jsonPath)) {
        $this->markTestSkipped('resources/data/elephpants.json not found');
    }

    File::copy($jsonPath, $backup);
    try {
        $minimal = ['elephpants' => [
            ['id' => 9999, 'name' => 'Test Elephant', 'description' => 'Desc', 'sponsor' => 'Sponsor', 'year' => 2020],
        ]];
        File::put($jsonPath, json_encode($minimal, JSON_THROW_ON_ERROR));

        $this->artisan('elephpants:read')->assertSuccessful();

        $elephpant = Elephpant::find(9999);
        expect($elephpant)->not->toBeNull()
            ->and($elephpant->name)->toBe('Test Elephant')
            ->and($elephpant->year)->toBe(2020)
            ->and($elephpant->image)->toBeNull();
    } finally {
        File::move($backup, $jsonPath);
    }
});

test('elephpants read command processes image when present', function (): void {
    $imageMock = Mockery::mock();
    $imageMock->shouldReceive('fit')->with(300)->andReturnSelf();
    $imageMock->shouldReceive('save')->andReturnSelf();

    Image::shouldReceive('make')->andReturn($imageMock);

    $jsonPath = resource_path('data/elephpants.json');
    $backup = $jsonPath.'.bak';
    if (! is_file($jsonPath)) {
        $this->markTestSkipped('resources/data/elephpants.json not found');
    }

    File::copy($jsonPath, $backup);
    try {
        $minimal = ['elephpants' => [
            ['id' => 9998, 'name' => 'With Image', 'description' => '', 'sponsor' => '', 'year' => 2021, 'image' => 'test.jpg'],
        ]];
        File::put($jsonPath, json_encode($minimal, JSON_THROW_ON_ERROR));

        $this->artisan('elephpants:read')->assertSuccessful();

        $elephpant = Elephpant::find(9998);
        expect($elephpant)->not->toBeNull()
            ->and($elephpant->name)->toBe('With Image')
            ->and($elephpant->image)->toBe('9998-with-image.jpg');
    } finally {
        File::move($backup, $jsonPath);
    }
});
