<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

final class EntityGenerator
{
    public function generate(string $module, string $name): void
    {
        $path = PathResolver::modulePath($module) . "/Domain/Entities/$name.php";

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/domain/entity.stub',
            [
                'module' => $module,
                'entity' => $name,
            ]
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}