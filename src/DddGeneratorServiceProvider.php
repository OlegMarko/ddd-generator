<?php

namespace Fixik\DddGenerator;

use Fixik\DddGenerator\Support\{EventListenerDiscovery,ModuleDiscovery};
use Fixik\DddGenerator\Commands\{CacheEventListenersCommand,
    CacheModulesCommand,
    MakeCommandCommand,
    MakeDddCommand,
    MakeDtoCommand,
    MakeEntityCommand,
    MakeEventCommand,
    MakeListenerCommand,
    MakeMapperCommand,
    MakeModelCommand,
    MakeModuleCommand,
    MakeQueryCommand,
    MakeRepositoryCommand,
    MakeUseCaseCommand,
    MakeValueObjectCommand};
use Illuminate\Support\ServiceProvider;

class DddGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ddd.php' => config_path('ddd.php'),
        ], 'config');

        if (config('ddd.auto_discovery.enabled')) {
            ModuleDiscovery::discover($this->app);
        }

        $this->app->booted(function () {
            EventListenerDiscovery::register();
        });
    }

    public function register(): void
    {
        $this->commands([
            MakeModuleCommand::class,
            MakeEntityCommand::class,
            MakeCommandCommand::class,
            MakeQueryCommand::class,
            MakeRepositoryCommand::class,
            MakeUseCaseCommand::class,
            MakeDddCommand::class,
            MakeValueObjectCommand::class,
            MakeEventCommand::class,
            MakeDtoCommand::class,
            MakeModelCommand::class,
            MakeListenerCommand::class,
            MakeMapperCommand::class,
            CacheModulesCommand::class,
            CacheEventListenersCommand::class,
        ]);
    }
}
