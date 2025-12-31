<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\CqrsGenerator;
use Illuminate\Console\Command;

class MakeCommandCommand extends Command
{
    protected $signature = 'ddd:make-command {module} {name}';
    protected $description = 'Create CQRS command';

    public function handle(CqrsGenerator $generator): int
    {
        $generator->command($this->argument('module'), $this->argument('name'));
        $generator->commandHandler($this->argument('module'), $this->argument('name'));

        $this->info('Command & handler created');

        return self::SUCCESS;
    }
}
