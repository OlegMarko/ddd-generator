<?php

namespace Fixik\DddGenerator\Support;

class PathResolver
{
    public static function modulePath(string $module): string
    {
        return config('ddd.base_path', app_path('Modules')) . "/$module";
    }
}
