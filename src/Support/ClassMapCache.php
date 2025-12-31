<?php

namespace Fixik\DddGenerator\Support;

use Illuminate\Support\Facades\File;

final class ClassMapCache
{
    public static function path(): string
    {
        return config('ddd.class_map.cache_path', base_path('bootstrap/cache/ddd-class-map.php'));
    }

    public static function isEnabled(): bool
    {
        return config('ddd.class_map.cache', true);
    }

    public static function get(): array
    {
        if (!self::isEnabled()) {
            return [];
        }

        $path = self::path();
        if ($path && File::exists($path)) {
            return require $path;
        }

        return [];
    }

    public static function write(array $map): void
    {
        if (!self::isEnabled()) {
            return;
        }

        $path = self::path();
        if (!$path) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put(
            $path,
            '<?php return ' . var_export($map, true) . ';'
        );
    }
}
