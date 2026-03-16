<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Store\Addons\Manifest;

class AddonsDiscover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:addons:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the cached addon package manifest';

    /**
     * Execute the console command.
     */
    public function handle(Manifest $manifest)
    {
        $manifest->build();

        foreach (array_keys($manifest->manifest) as $package) {
            $this->components->info("Discovered Addon: <info>{$package}</info>");
        }

        $this->components->info('Addon manifest generated successfully.');

        return self::SUCCESS;
    }
}
