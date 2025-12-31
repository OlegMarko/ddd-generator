<?php

namespace Fixik\DddGenerator\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Foundation\Application;

class ModuleDiscovery
{
    public static function discover(Application $app): void
    {
        $providers = self::getProviders();

        foreach ($providers as $provider) {
            if (class_exists($provider)) {
                $app->register($provider);
            }
        }
    }

    public static function rebuildCache(): array
    {
        $modulesPath = config('ddd.base_path', app_path('Modules'));
        $providers = [];

        if (!$modulesPath || !is_dir($modulesPath)) {
            return [];
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $module = basename($moduleDir);
            $provider = NamespaceResolver::serviceProvider($module);

            if (File::exists("$moduleDir/{$module}ServiceProvider.php")) {
                $providers[] = $provider;
            }
        }

        self::writeCache($providers);

        return $providers;
    }

    private static function getProviders(): array
    {
        if (
            config('ddd.auto_discovery.cache', true) &&
            File::exists(config('ddd.auto_discovery.cache_path', base_path('bootstrap/cache/ddd-modules.php')))
        ) {
            return require config('ddd.auto_discovery.cache_path', base_path('bootstrap/cache/ddd-modules.php'));
        }

        return self::rebuildCache();
    }

    private static function writeCache(array $providers): void
    {
        if (!config('ddd.auto_discovery.cache', true)) {
            return;
        }

        $path = config('ddd.auto_discovery.cache_path', base_path('bootstrap/cache/ddd-modules.php'));

        if (!$path) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put(
            $path,
            '<?php return ' . var_export($providers, true) . ';'
        );
    }
}
