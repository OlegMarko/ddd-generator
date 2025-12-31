<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\CqrsGenerator;
use Illuminate\Console\Command;

class MakeQueryCommand extends Command
{
    protected $signature = 'ddd:make-query {module} {name}';
    protected $description = 'Create CQRS query';

    public function handle(CqrsGenerator $generator): int
    {
        $generator->query($this->argument('module'), $this->argument('name'));
        $generator->queryHandler($this->argument('module'), $this->argument('name'));

        $this->info('Query & handler created');

        return self::SUCCESS;
    }
}
