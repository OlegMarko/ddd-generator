<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

final class ServiceProviderGenerator
{
    private function path(string $module): string
    {
        return PathResolver::modulePath($module)
            . "/{$module}ServiceProvider.php";
    }

    private function stubPath(): string
    {
        return __DIR__ . '/../../stubs/module/service_provider.stub';
    }

    /**
     * Generate empty ServiceProvider (no bindings).
     */
    public function generate(string $module): void
    {
        $path = $this->path($module);

        if (File::exists($path)) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            $this->stubPath(),
            [
                'module'    => $module,
                'namespace' => NamespaceResolver::module($module),
                'uses'      => null,
                'bindings'  => null,
            ]
        );

        File::put($path, $content);
    }

    public function bindRepository(
        string $module,
        string $repository,
        string $implementation
    ): void {
        $this->generate($module);

        $path = $this->path($module);
        $existing = File::get($path);

        if (str_contains($existing, "{$repository}::class")) {
            return;
        }

        $repositoryUse = NamespaceResolver::repository($module, $repository);
        $implementationUse = NamespaceResolver::repositoryImplementation($module, $implementation);

        $uses = <<<PHP
use {$repositoryUse};
use {$implementationUse};
PHP;

        $bindings = <<<PHP
        \$this->app->bind(
            {$repository}::class,
            {$implementation}::class
        );
PHP;

        $content = StubRenderer::render(
            $this->stubPath(),
            [
                'module'    => $module,
                'namespace' => NamespaceResolver::module($module),
                'uses'      => $uses,
                'bindings'  => $bindings,
            ]
        );

        File::put($path, $content);
    }
}
