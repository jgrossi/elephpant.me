<?php

namespace App\Console\Services;

use App\Elephpant;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Output\OutputInterface;

class ElephpantImporter
{
    public function __construct(protected array $elephpants, protected OutputInterface $output)
    {
    }

    public function import(): void
    {
        foreach ($this->elephpants as $elephpant) {
            Elephpant::query()
                ->updateOrCreate(
                    ['id' => (int) $elephpant->id],
                    [
                        'name'        => $elephpant->name,
                        'format'      => $elephpant->format,
                        'description' => $elephpant->description,
                        'sponsor'     => $elephpant->sponsor,
                        'year'        => (int) $elephpant->year,
                        'image'       => $this->processImage($elephpant),
                    ]
                );
        }

        $this->output->write(PHP_EOL);
    }

    private function processImage(object $elephpant): ?string
    {
        if (empty($elephpant->image)) {
            return null;
        }

        $localImagePath = public_path(sprintf('/images/elephpants/%s', $elephpant->image));
        try {
            $image = Image::make($localImagePath);
        } catch (NotReadableException $notReadableException) {
            throw new \RuntimeException(sprintf('%s on ID %s with path %s', $notReadableException->getMessage(), $elephpant->id, $elephpant->image), $notReadableException->getCode(), $notReadableException);
        }

        $image->fit(300);
        $imageName = sprintf('%d-%s.jpg', $elephpant->id, Str::slug($elephpant->name));
        $filePath = storage_path(sprintf('app/public/elephpants/%s', $imageName));
        File::makeDirectory(dirname($filePath), 0755, true, true);
        $image->save($filePath);
        $this->output->write('.');

        return $imageName;
    }
}
