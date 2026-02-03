<?php

namespace Fixik\DddGenerator\Generators;

use Illuminate\Filesystem\Filesystem;
use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Support\StubRenderer;

final class ResourceGenerator
{
    public function __construct(
        private readonly Filesystem   $files,
        private readonly StubRenderer $stubs,
    ) {}

    public function generate(string $module, string $name): void
    {
        $path = $this->getPath($module, $name);

        if ($this->files->exists($path)) {
            return;
        }

        $this->files->ensureDirectoryExists(
            dirname($path)
        );

        $this->files->put(
            $path,
            $this->stubs->render(__DIR__ . '/../../stubs/http/resource.stub', [
                'namespace' => NamespaceResolver::httpResourceNamespace($module),
                'resource'  => $name,
            ])
        );
    }

    private function getPath(string $module, string $name): string
    {
        return app_path(sprintf(
            'Modules/%s/Infrastructure/Http/Resources/%s.php',
            $module,
            $name
        ));
    }
}
