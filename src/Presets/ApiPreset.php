<?php

namespace Fixik\DddGenerator\Presets;

use Illuminate\Support\Facades\Artisan;

final class ApiPreset implements PresetInterface
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

        Artisan::call('ddd:make-cqrs-command', [
            'module' => $module,
            'name' => 'Create' . $entity,
            '--entity' => $entity,
        ]);

        Artisan::call('ddd:make-cqrs-query', [
            'module' => $module,
            'name' => 'Get' . $entity,
            '--entity' => $entity,
        ]);

        Artisan::call('ddd:make-model', [
            'module' => $module,
            'name' => $entity,
        ]);

        Artisan::call('ddd:make-mapper', [
            'module' => $module,
            'entity' => $entity,
        ]);
    }
}
