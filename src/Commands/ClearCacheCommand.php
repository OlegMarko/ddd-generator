<?php

namespace Fixik\DddGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearCacheCommand extends Command
{
    protected $signature = 'ddd:cache-clear';
    protected $description = 'Clear all DDD generator caches';

    public function handle(): int
    {
        $paths = [
            config('ddd.auto_discovery.cache_path'),
            config('ddd.event_listeners.cache_path'),
            config('ddd.class_map.cache_path'),
        ];

        foreach ($paths as $path) {
            if ($path && File::exists($path)) {
                File::delete($path);
                $this->line("Deleted: {$path}");
            }
        }

        $this->info('DDD caches cleared successfully.');

        return self::SUCCESS;
    }
}
