<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

class UseCaseGenerator
{
    public function generate(string $module, string $name, ?string $entity = null): void
    {
        $repository = $entity ? "{$entity}Repository" : '/* Repository */';

        $path = PathResolver::modulePath($module) . "/Application/UseCases/$name.php";

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/application/usecase.stub',
            [
                'module' => $module,
                'usecase' => $name,
                'repository' => $repository,
            ]
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}
