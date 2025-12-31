<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\ModuleDiscovery;
use Illuminate\Console\Command;

class CacheModulesCommand extends Command
{
    protected $signature = 'ddd:modules-cache';
    protected $description = 'Rebuild DDD modules discovery cache';

    public function handle(): int
    {
        $providers = ModuleDiscovery::rebuildCache();

        $this->info('DDD modules cache rebuilt');
        $this->line('Found providers:');

        foreach ($providers as $provider) {
            $this->line(" - $provider");
        }

        return self::SUCCESS;
    }
}
