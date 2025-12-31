<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModelCommand extends Command
{
    protected $signature = 'ddd:make-model {module} {name}';
    protected $description = 'Create an Eloquent Model (Infrastructure)';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));
        $table = Str::snake(Str::pluralStudly($name));

        $path = PathResolver::modulePath($module) . "/Infrastructure/Persistence/Models/$name.php";

        if (File::exists($path)) {
            $this->error('Model already exists');
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/infrastructure/model.stub',
            [
                'module' => $module,
                'model' => $name,
                'table' => $table,
            ]
        );

        File::put($path, $content);

        $this->info("Model $name created");

        return self::SUCCESS;
    }
}
