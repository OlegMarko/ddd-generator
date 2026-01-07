<?php

namespace Fixik\DddGenerator\Presets;

use Illuminate\Support\Facades\Artisan;

final class CrudPreset implements PresetInterface
{
    public function generate(string $module, string $entity): void
    {
        Artisan::call('ddd:make-module', [
            'name' => $module,
        ]);

        // CRUD preset will be expanded in next PR
    }
}
