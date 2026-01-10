<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\EventListenerDiscovery;
use Illuminate\Console\Command;

class CacheEventListenersCommand extends Command
{
    protected $signature = 'ddd:event-listeners-cache';
    protected $description = 'Rebuild domain event listeners cache';

    public function handle(): int
    {
        $map = EventListenerDiscovery::rebuildCache();

        $this->info('Event listeners cache rebuilt');

        if (app()->environment(['local', 'testing'])) {
            $this->warn('⚠️ This command is intended for PRODUCTION use only.');
            $this->warn('⚠️ Current environment: ' . app()->environment());
            $this->newLine();
        }

        foreach ($map as $event => $listeners) {
            $this->line($event);
            foreach ($listeners as $listener) {
                $this->line("  - $listener");
            }
        }

        $this->info('DDD event -> listeners cache generated successfully.');

        return self::SUCCESS;
    }
}
