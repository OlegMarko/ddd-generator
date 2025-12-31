<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEventCommand extends Command
{
    protected $signature = 'ddd:make-event {module} {name}';
    protected $description = 'Create a Domain Event';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));

        $path = PathResolver::modulePath($module) . "/Domain/Events/$name.php";

        if (File::exists($path)) {
            $this->error('Event already exists');
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = StubRenderer::render(
            __DIR__ . '/../../stubs/domain/event.stub',
            [
                'module' => $module,
                'event' => $name,
            ]
        );

        File::put($path, $content);

        $this->info("Event {$name} created");

        return self::SUCCESS;
    }
}
