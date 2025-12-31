<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\ModuleGenerator;
use Illuminate\Console\Command;

class MakeModuleCommand extends Command
{
    protected $signature = 'ddd:make-module {name} {--cqrs}';
    protected $description = 'Create DDD module';

    public function handle(ModuleGenerator $generator): int
    {
        $generator->generate(
            $this->argument('name'),
            $this->option('cqrs')
        );

        $this->info('Module created');

        return self::SUCCESS;
    }
}
