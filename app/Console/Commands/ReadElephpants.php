<?php

namespace App\Console\Commands;

use App\Elephpant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ReadElephpants extends Command
{
    protected $signature = 'elephpants:read';
    protected $description = 'Read elePHPants in the JSON file';

    public function handle(): void
    {
        $jsonFile = resource_path('data/elephpants.json');
        $elephpants = json_decode(file_get_contents($jsonFile))->elephpants;

        foreach ($elephpants as $elephpant) {
            Elephpant::query()
                ->updateOrCreate(
                    ['id' => (int)$elephpant->id],
                    [
                        'name' => $elephpant->name,
                        'description' => $elephpant->description,
                        'sponsor' => $elephpant->sponsor,
                        'year' => (int)$elephpant->year,
                        'image' => $this->processImage($elephpant),
                    ]
                );
        }
    }

    private function processImage(object $elephpant): ?string
    {
        if (isset($elephpant->image) && $elephpant->image) {
            $localImagePath = public_path(sprintf('/images/elephpants/%s', $elephpant->image));
            $image = Image::make($localImagePath);
            $image->fit(300);
            $imageName = sprintf('%d-%s.jpg', $elephpant->id, Str::slug($elephpant->name));
            $filePath = storage_path(sprintf('app/public/elephpants/%s', $imageName));
            File::makeDirectory(dirname($filePath), 0755, true, true);
            $image->save($filePath);
            $this->output->write('.', false);

            return $imageName;
        }

        return null;
    }
}
