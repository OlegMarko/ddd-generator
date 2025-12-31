<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeMapperCommand extends Command
{
    protected $signature = 'ddd:make-mapper {module} {entity}';
    protected $description = 'Create Entity ↔ Model mapper';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $entity = ucfirst($this->argument('entity'));

        $path = PathResolver::modulePath($module) . "/Infrastructure/Persistence/Mappers/{$entity}Mapper.php";

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/infrastructure/mapper.stub',
            [
                'module' => $module,
                'entity' => $entity,
            ]
        );

        File::put($path, $content);

        $this->info("Mapper {$entity}Mapper created");

        return self::SUCCESS;
    }
}
