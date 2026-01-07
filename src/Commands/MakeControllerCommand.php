<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\ControllerGenerator;
use Illuminate\Console\Command;

class MakeControllerCommand extends Command
{
    protected $signature = 'ddd:make-controller {module} {name}';
    protected $description = 'Create API controller';

    public function handle(ControllerGenerator $generator): int
    {
        $generator->generate(
            module: $this->argument('module'),
            name: $this->argument('name')
        );

        $this->info('Controller created');

        return self::SUCCESS;
    }
}
