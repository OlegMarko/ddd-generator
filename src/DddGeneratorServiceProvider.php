<?php

namespace Fixik\DddGenerator;

use Fixik\DddGenerator\Support\{EventListenerDiscovery,ModuleDiscovery};
use Fixik\DddGenerator\Commands\{CacheEventListenersCommand,
    CacheModulesCommand,
    MakeControllerCommand,
    MakeCqrsCommand,
    MakeDddCommand,
    MakeDtoCommand,
    MakeEntityCommand,
    MakeEventCommand,
    MakeListenerCommand,
    MakeMapperCommand,
    MakeModelCommand,
    MakeModuleCommand,
    MakeCqrsQueryCommand,
    MakeRepositoryCommand,
    MakeRequestCommand,
    MakeResourceCommand,
    MakeRouteCommand,
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
            CacheEventListenersCommand::class,
            CacheModulesCommand::class,
            MakeControllerCommand::class,
            MakeCqrsCommand::class,
            MakeDddCommand::class,
            MakeDtoCommand::class,
            MakeEntityCommand::class,
            MakeEventCommand::class,
            MakeListenerCommand::class,
            MakeMapperCommand::class,
            MakeModelCommand::class,
            MakeModuleCommand::class,
            MakeCqrsQueryCommand::class,
            MakeRepositoryCommand::class,
            MakeRequestCommand::class,
            MakeResourceCommand::class,
            MakeRouteCommand::class,
            MakeUseCaseCommand::class,
            MakeValueObjectCommand::class,
        ]);
    }
}
