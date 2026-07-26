<?php

namespace App\Console\Commands;

use App\Console\Services\ElephpantImporter;
use Illuminate\Console\Command;

class ReadElephpants extends Command
{
    #[\Override]
    protected $signature = 'elephpants:read';

    #[\Override]
    protected $description = 'Read elePHPants in the JSON file';

    public function handle(): void
    {
        $jsonFile = resource_path('data/elephpants.json');
        if (!file_exists($jsonFile)) {
            throw new \RuntimeException($jsonFile . ' does not exist');
        }

        $elephpants = json_decode(file_get_contents($jsonFile))->elephpants;
        $service = new ElephpantImporter($elephpants, $this->output);
        $service->import();
    }
}
