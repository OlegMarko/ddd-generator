<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeListenerCommand extends Command
{
    protected $signature = 'ddd:make-listener {module} {event} {listener} {--queued : Make listener queueable}';

    protected $description = 'Create an Event Listener for a Domain Event';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $event = ucfirst($this->argument('event'));
        $listener = ucfirst($this->argument('listener'));

        $path = PathResolver::modulePath($module) . "/Application/Listeners/{$listener}Listener.php";

        $stubFile = $this->option('queued')
            ? __DIR__ . '/../../stubs/application/listener.stub'
            : __DIR__ . '/../../stubs/application/listener_sync.stub';

        $content = StubRenderer::render($stubFile, [
            'module' => $module,
            'event' => $event,
            'listener' => $listener,
        ]);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);

        $this->info("Listener {$listener}Listener created");

        return self::SUCCESS;
    }
}
