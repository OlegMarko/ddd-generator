<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Support\PathResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRouteCommand extends Command
{
    protected $signature = 'ddd:make-route
        {module}
        {entity}
        {--style= : Route style (api|crud)}';
    protected $description = 'Register module API routes';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $entity = ucfirst($this->argument('entity'));
        $style = $this->option('style') ?? 'api';

        if (!in_array(strtolower($style), ['api', 'crud'], true)) {
            $this->error("Unknown style [$style]");
            return self::FAILURE;
        }

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

        if (strtolower($style) === 'crud') {
            $routes = [
                "Route::get('{$resource}', [{$controllerFqn}::class, 'index']);",
                "Route::post('{$resource}', [{$controllerFqn}::class, 'store']);",
                "Route::get('{$resource}/{id}', [{$controllerFqn}::class, 'show']);",
                "Route::put('{$resource}/{id}', [{$controllerFqn}::class, 'update']);",
                "Route::delete('{$resource}/{id}', [{$controllerFqn}::class, 'destroy']);",
            ];
        } else {
            $routes = [
                "Route::post('{$resource}', [{$controllerFqn}::class, 'store']);",
                "Route::get('{$resource}/{id}', [{$controllerFqn}::class, 'show']);",
            ];
        }

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
