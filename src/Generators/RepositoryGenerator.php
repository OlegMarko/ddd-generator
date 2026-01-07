<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

final class RepositoryGenerator
{
    public function __construct(
        private readonly ServiceProviderGenerator $providerGenerator
    ) {}

    public function generate(string $module, string $entity): void
    {
        $repositoryClass = "{$entity}Repository";
        $implementationClass = "Eloquent{$entity}Repository";

        $this->put(
            $module,
            "Domain/Repositories/{$repositoryClass}.php",
            'domain/repository.stub',
            [
                'entity'     => $entity,
                'repository' => $repositoryClass,
            ]
        );

        $this->put(
            $module,
            "Infrastructure/Persistence/Repositories/{$implementationClass}.php",
            'infrastructure/repository.stub',
            [
                'entity'         => $entity,
                'repository'     => $repositoryClass,
                'implementation' => $implementationClass,
            ]
        );

        $this->providerGenerator->bindRepository(
            $module,
            $repositoryClass,
            $implementationClass
        );
    }

    private function put(
        string $module,
        string $relativePath,
        string $stub,
        array  $data
    ): void {
        $path = PathResolver::modulePath($module) . "/{$relativePath}";

        if (File::exists($path)) {
            return;
        }

        $content = StubRenderer::render(
            __DIR__ . "/../../stubs/{$stub}",
            array_merge(
                ['module' => $module],
                $data
            )
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }
}
