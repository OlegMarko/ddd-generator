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

        foreach ($map as $event => $listeners) {
            $this->line($event);
            foreach ($listeners as $listener) {
                $this->line("  - $listener");
            }
        }

        return self::SUCCESS;
    }
}
