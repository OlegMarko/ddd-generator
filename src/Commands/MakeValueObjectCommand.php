<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeValueObjectCommand extends Command
{
    protected $signature = 'ddd:make-value-object {module : Module name} {name : Value Object name} {--type=string : Underlying value type}';

    protected $description = 'Create a Domain Value Object';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));
        $type = $this->option('type');

        $path = PathResolver::modulePath($module) . "/Domain/ValueObjects/$name.php";

        if (File::exists($path)) {
            $this->error('ValueObject already exists');
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/domain/value_object.stub',
            [
                'module' => $module,
                'valueObject' => $name,
                'type' => $type,
            ]
        );

        File::put($path, $content);

        $this->info("ValueObject $name created");

        return self::SUCCESS;
    }
}
