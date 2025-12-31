<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

class ServiceProviderGenerator
{
    public function bindRepository(
        string $module,
        string $repository,
        string $implementation
    ): void
    {
        $providerPath = PathResolver::modulePath($module) . "/{$module}ServiceProvider.php";

        if (!File::exists($providerPath)) {
            $this->createProvider(
                $module,
                $repository,
                $implementation,
                $providerPath
            );
            return;
        }

        $this->appendBind(
            $providerPath,
            $repository,
            $implementation
        );
    }

    private function createProvider(
        string $module,
        string $repository,
        string $implementation,
        string $path
    ): void
    {
        File::put(
            $path,
            StubRenderer::render(
                __DIR__ . '/../../stubs/module/service_provider.stub',
                compact('module', 'repository', 'implementation')
            )
        );
    }

    private function appendBind(
        string $path,
        string $repository,
        string $implementation
    ): void
    {
        $content = File::get($path);
        if (str_contains($content, $repository)) {
            return;
        }

        $bind = <<< PHP

        \$this->app->bind(
            $repository::class,
            $implementation::class
        );
PHP;

        $content = preg_replace(
            '/public function register\(\): void\s*\{/',
            "public function register(): void\n    {{$bind}",
            $content,
            1
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}
