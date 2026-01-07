<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Support\PathResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRouteCommand extends Command
{
    protected $signature = 'ddd:make-route {module} {entity}';
    protected $description = 'Register module API routes';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $entity = ucfirst($this->argument('entity'));

        $routesPath = PathResolver::modulePath($module) . '/Infrastructure/Http/routes.php';

        File::ensureDirectoryExists(dirname($routesPath));

        if (!File::exists($routesPath)) {
            File::put(
                $routesPath,
                "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n"
            );
        }

        $controllerFqn =
            NamespaceResolver::module($module)
            . "\\Infrastructure\\Http\\Controllers\\{$entity}Controller";

        $resource = Str::lower(Str::plural($entity));

        $routes = [
            "Route::post('{$resource}', [{$controllerFqn}::class, 'store']);",
            "Route::get('{$resource}/{id}', [{$controllerFqn}::class, 'show']);",
        ];

        $content = File::get($routesPath);

        foreach ($routes as $route) {
            if (!str_contains($content, $route)) {
                File::append($routesPath, $route . PHP_EOL);
            }
        }

        $this->info('API routes registered');

        return self::SUCCESS;
    }
}
