<?php

namespace Fixik\DddGenerator\Presets;

use Illuminate\Support\Facades\Artisan;

final class ApiHttpPreset implements PresetInterface
{
    public function generate(string $module, string $entity): void
    {
        Artisan::call('ddd:make-module', [
            'name' => $module,
        ]);

        Artisan::call('ddd:make-entity', [
            'module' => $module,
            'name' => $entity,
        ]);

        Artisan::call('ddd:make-repository', [
            'module' => $module,
            'entity' => $entity,
        ]);

        Artisan::call('ddd:make-model', [
            'module' => $module,
            'name' => $entity,
        ]);

        Artisan::call('ddd:make-mapper', [
            'module' => $module,
            'entity' => $entity,
        ]);

        Artisan::call('ddd:make-controller', [
            'module' => $module,
            'name' => $entity . 'Controller',
            '--style' => 'api',
        ]);

        Artisan::call('ddd:make-request', [
            'module' => $module,
            'name' => 'Store' . $entity . 'Request',
        ]);

        Artisan::call('ddd:make-resource', [
            'module' => $module,
            'name' => $entity . 'Resource',
        ]);

        Artisan::call('ddd:make-route', [
            'module' => $module,
            'entity' => $entity,
            '--style' => 'api',
        ]);
    }
}
