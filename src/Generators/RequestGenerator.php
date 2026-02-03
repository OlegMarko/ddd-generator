<?php

namespace Fixik\DddGenerator\Generators;

use Illuminate\Filesystem\Filesystem;
use Fixik\DddGenerator\Support\NamespaceResolver;

class RequestGenerator
{
    public function __construct(
        protected Filesystem $files,
    ) {}

    public function generate(string $module, string $name): void
    {
        $path = $this->path($module, $name);

        if ($this->files->exists($path)) {
            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put(
            $path,
            $this->stub($module, $name)
        );
    }

    protected function path(string $module, string $name): string
    {
        return app_path(
            "Modules/$module/Infrastructure/Http/Requests/$name.php"
        );
    }

    protected function stub(string $module, string $request): string
    {
        return str_replace(
            [
                '{{ namespace }}',
                '{{ request }}',
            ],
            [
                NamespaceResolver::httpRequestNamespace($module),
                $request,
            ],
            file_get_contents(__DIR__.'/../../stubs/http/request.stub')
        );
    }
}
