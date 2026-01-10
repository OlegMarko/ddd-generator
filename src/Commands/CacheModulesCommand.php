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

        if (app()->environment(['local', 'testing'])) {
            $this->warn('⚠️ This command is intended for PRODUCTION use only.');
            $this->warn('⚠️ Current environment: ' . app()->environment());
            $this->newLine();
        }

        $this->line('Found providers:');

        foreach ($providers as $provider) {
            $this->line(" - $provider");
        }

        $this->info('DDD modules cache generated successfully.');

        return self::SUCCESS;
    }
}
