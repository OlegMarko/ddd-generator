<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\CqrsGenerator;
use Illuminate\Console\Command;

class MakeCqrsCommand extends Command
{
    protected $signature = 'ddd:make-cqrs-command {module} {name} {--entity=}';
    protected $description = 'Create CQRS command';

    public function handle(CqrsGenerator $generator): int
    {
        $generator->command(
            $this->argument('module'),
            $this->argument('name'),
            $this->option('entity')
        );

        $generator->commandHandler(
            $this->argument('module'),
            $this->argument('name'),
            $this->option('entity')
        );

        $this->info('Command & handler created');

        return self::SUCCESS;
    }
}
