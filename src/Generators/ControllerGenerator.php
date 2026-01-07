<?php

namespace Fixik\DddGenerator\Generators;

use Illuminate\Filesystem\Filesystem;
use Fixik\DddGenerator\Support\NamespaceResolver;

final class ControllerGenerator
{
    public function __construct(
        protected Filesystem $files,
    ) {}

    public function generate(string $module, string $name, ?string $entity = null): void
    {
        $entity ??= str_replace('Controller', '', $name);

        $path = $this->path($module, $name);

        if ($this->files->exists($path)) {
            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put(
            $path,
            $this->stub($module, $name, $entity)
        );
    }

    protected function path(string $module, string $name): string
    {
        return app_path(
            "Modules/$module/Infrastructure/Http/Controllers/$name.php"
        );
    }

    protected function stub(string $module, string $controller, string $entity): string
    {
        return str_replace(
            [
                '{{ namespace }}',
                '{{ controller }}',
                '{{ entity }}',

                '{{ requestNamespace }}',
                '{{ resourceNamespace }}',
                '{{ commandNamespace }}',
                '{{ queryNamespace }}',

                '{{ request }}',
                '{{ resource }}',
                '{{ command }}',
                '{{ query }}',
            ],
            [
                NamespaceResolver::httpController($module, $controller),
                $controller,
                $entity,

                NamespaceResolver::httpRequest($module, 'Store'.$entity.'Request'),
                NamespaceResolver::httpResource($module, $entity.'Resource'),
                NamespaceResolver::command($module, 'Create'.$entity),
                NamespaceResolver::query($module, 'Get'.$entity),

                'Store'.$entity.'Request',
                $entity.'Resource',
                'Create'.$entity,
                'Get'.$entity,
            ],
            file_get_contents(__DIR__.'/../../stubs/http/controller.stub')
        );
    }
}
