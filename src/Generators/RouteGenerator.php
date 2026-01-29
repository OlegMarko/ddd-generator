<?php

namespace Fixik\DddGenerator\Generators;

use Illuminate\Filesystem\Filesystem;

class RouteGenerator
{
    public function __construct(
        protected Filesystem $files,
    ) {}

    public function generate(string $module, string $entity, ?string $style = null): void
    {
        $path = $this->path($module);

        if ($this->files->exists($path)) {
            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put(
            $path,
            $this->stub($module, $entity, $style)
        );
    }

    protected function path(string $module): string
    {
        return app_path(
            "Modules/$module/Infrastructure/Http/routes.php"
        );
    }

    protected function stub(string $module, string $entity, ?string $style): string
    {
        $style = $style !== null ? strtolower($style) : 'api';
        $stubPath = $style === 'crud'
            ? __DIR__.'/../../stubs/http/routes_crud.stub'
            : __DIR__.'/../../stubs/http/routes.stub';

        return str_replace(
            ['{{ entity }}', '{{ entityPlural }}'],
            [
                $entity,
                strtolower($entity) . 's',
            ],
            file_get_contents($stubPath)
        );
    }
}
