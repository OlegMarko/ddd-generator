<?php

namespace Fixik\DddGenerator\Presets;

use Illuminate\Support\Facades\Artisan;

final class CorePreset implements PresetInterface
{
    public function generate(string $module, string $entity): void
    {
        Artisan::call('ddd:make-module', [
            'name' => $module,
        ]);

        Artisan::call('ddd:make-entity', [
            'module' => $module,
            'name'   => $entity,
        ]);
    }
}
