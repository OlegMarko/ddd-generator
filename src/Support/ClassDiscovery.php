<?php

namespace Fixik\DddGenerator\Support;

use Illuminate\Support\Facades\File;

final class ClassDiscovery
{
    public static function load(string $class, string $file): bool
    {
        if (class_exists($class, false)) {
            return true;
        }

        if (!File::exists($file)) {
            return false;
        }

        require_once $file;

        return class_exists($class, false);
    }
}
