<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

class RepositoryGenerator
{
    public function __construct(
        private readonly ServiceProviderGenerator $providerGenerator
    ) {}

    public function generate(string $module, string $entity): void
    {
        $repository = "{$entity}Repository";
        $implementation = "Eloquent{$entity}Repository";

        $this->put(
            $module,
            "Domain/Repositories/$repository.php",
            'domain/repository.stub',
            [
                'entity' => $entity,
                'repository' => $repository,
            ]
        );

        $this->put(
            $module,
            "Infrastructure/Persistence/Repositories/$implementation.php",
            'infrastructure/repository.stub',
            [
                'entity' => $entity,
                'repository' => $repository,
                'implementation' => $implementation,
            ]
        );

        $this->providerGenerator->bindRepository(
            $module,
            $repository,
            $implementation
        );
    }

    private function put(
        string $module,
        string $relativePath,
        string $stub,
        array  $data
    ): void
    {
        $path = PathResolver::modulePath($module) . "/$relativePath";

        $content = StubRenderer::render(
            __DIR__ . "/../../stubs/$stub",
            array_merge(['module' => $module], $data)
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}
