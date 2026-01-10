<?php

namespace Fixik\DddGenerator\Listeners;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;

class OptimizeListener
{
    public function handle(CommandFinished $event): void
    {
        if ($event->command !== 'optimize') {
            return;
        }

        if (!config('ddd.auto_discovery.cache')) {
            return;
        }

        Artisan::call('ddd:modules-cache');
        Artisan::call('ddd:event-listeners-cache');
    }
}
