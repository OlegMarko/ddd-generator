<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\UseCaseGenerator;
use Illuminate\Console\Command;

class MakeUseCaseCommand extends Command
{
    protected $signature = 'ddd:make-usecase {module} {name} {--entity=}';
    protected $description = 'Create application use case';

    public function handle(UseCaseGenerator $generator): int
    {
        $generator->generate(
            module: $this->argument('module'),
            name: $this->argument('name'),
            entity: $this->option('entity')
        );

        $this->info('Use case created');

        return self::SUCCESS;
    }
}
