<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDtoCommand extends Command
{
    protected $signature = 'ddd:make-dto {module} {name}';
    protected $description = 'Create an Application DTO';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $path = PathResolver::modulePath($module) . "/Application/DTO/$name.php";

        if (File::exists($path)) {
            $this->error('DTO already exists');
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/application/dto.stub',
            [
                'module' => $module,
                'dto' => $name,
                'properties' => '// TODO: properties',
            ]
        );

        File::put($path, $content);

        $this->info("DTO {$name} created");

        return self::SUCCESS;
    }
}
