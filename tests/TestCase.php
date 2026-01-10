<?php

namespace Fixik\DddGenerator\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Fixik\DddGenerator\DddGeneratorServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DddGeneratorServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('ddd.base_path', app_path('Modules'));
        $app['config']->set('ddd.base_namespace', 'App\\Modules');

        $app['config']->set('ddd.auto_discovery.enabled', false);
        $app['config']->set('ddd.auto_discovery.cache', false);
        $app['config']->set('ddd.auto_discovery.cache_path', base_path('bootstrap/cache/ddd-modules.php'));

        $app['config']->set('ddd.event_listeners.cache', false);
        $app['config']->set('ddd.event_listeners.cache_path', base_path('bootstrap/cache/ddd-event-listeners.php'));

        $app['config']->set('ddd.class_map.cache', false);
        $app['config']->set('ddd.class_map.cache_path', base_path('bootstrap/cache/ddd-class-map.php'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up before each test to ensure isolation
        $this->cleanup();
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        $this->cleanup();
        
        parent::tearDown();
    }

    protected function cleanup(): void
    {
        if (is_dir(app_path('Modules'))) {
            File::deleteDirectory(app_path('Modules'));
        }

        $cacheFiles = [
            base_path('bootstrap/cache/ddd-modules.php'),
            base_path('bootstrap/cache/ddd-event-listeners.php'),
            base_path('bootstrap/cache/ddd-class-map.php'),
        ];
        
        foreach ($cacheFiles as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
}
