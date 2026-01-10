<?php

namespace Fixik\DddGenerator\Presets;

use Illuminate\Support\Facades\Artisan;

final class ApiHttpPreset implements PresetInterface
{
    public function generate(string $module, string $entity): void
    {
        Artisan::call('ddd:make', [
            'preset' => 'cqrs',
            'module' => $module,
            '--entity' => $entity,
        ]);

        Artisan::call('ddd:make-controller', [
            'module' => $module,
            'name' => $entity . 'Controller',
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
        ]);

        Artisan::call('ddd:make-route', [
            'module' => $module,
            'entity' => $entity,
        ]);
    }
}
