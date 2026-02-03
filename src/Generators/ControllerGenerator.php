<?php

namespace Fixik\DddGenerator\Generators;

use Illuminate\Filesystem\Filesystem;
use Fixik\DddGenerator\Support\NamespaceResolver;

final class ControllerGenerator
{
    public function __construct(
        protected Filesystem $files,
    ) {}

    public function generate(
        string $module,
        string $name,
        ?string $entity = null,
        ?string $style = null
    ): void
    {
        $entity ??= str_replace('Controller', '', $name);
        $style = $style !== null ? strtolower($style) : 'api';

        $path = $this->path($module, $name);

        if ($this->files->exists($path)) {
            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put(
            $path,
            $this->stub($module, $name, $entity, $style)
        );
    }

    protected function path(string $module, string $name): string
    {
        return app_path(
            "Modules/$module/Infrastructure/Http/Controllers/$name.php"
        );
    }

    protected function stub(
        string $module,
        string $controller,
        string $entity,
        string $style
    ): string
    {
        if ($style === 'crud') {
            return str_replace(
                [
                    '{{ namespace }}',
                    '{{ controller }}',
                    '{{ entity }}',
                    '{{ requestNamespace }}',
                    '{{ resourceNamespace }}',
                    '{{ repositoryNamespace }}',
                    '{{ entityNamespace }}',
                    '{{ request }}',
                    '{{ resource }}',
                    '{{ repository }}',
                ],
                [
                    NamespaceResolver::httpControllerNamespace($module),
                    $controller,
                    $entity,
                    NamespaceResolver::httpRequest($module, 'Store'.$entity.'Request'),
                    NamespaceResolver::httpResource($module, $entity.'Resource'),
                    NamespaceResolver::repository($module, $entity.'Repository'),
                    NamespaceResolver::entity($module, $entity),
                    'Store'.$entity.'Request',
                    $entity.'Resource',
                    $entity.'Repository',
                ],
                file_get_contents(__DIR__.'/../../stubs/http/controller_crud.stub')
            );
        }

        if ($style === 'api') {
            return str_replace(
                [
                    '{{ namespace }}',
                    '{{ controller }}',
                    '{{ entity }}',
                    '{{ requestNamespace }}',
                    '{{ resourceNamespace }}',
                    '{{ repositoryNamespace }}',
                    '{{ entityNamespace }}',
                    '{{ request }}',
                    '{{ resource }}',
                    '{{ repository }}',
                ],
                [
                    NamespaceResolver::httpControllerNamespace($module),
                    $controller,
                    $entity,
                    NamespaceResolver::httpRequest($module, 'Store'.$entity.'Request'),
                    NamespaceResolver::httpResource($module, $entity.'Resource'),
                    NamespaceResolver::repository($module, $entity.'Repository'),
                    NamespaceResolver::entity($module, $entity),
                    'Store'.$entity.'Request',
                    $entity.'Resource',
                    $entity.'Repository',
                ],
                file_get_contents(__DIR__.'/../../stubs/http/controller_api.stub')
            );
        }

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
                NamespaceResolver::httpControllerNamespace($module),
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
