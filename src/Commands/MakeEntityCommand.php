<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\EntityGenerator;
use Illuminate\Console\Command;

class MakeEntityCommand extends Command
{
    protected $signature = 'ddd:make-entity {module} {name}';

    public function handle(EntityGenerator $generator): int
    {
        $generator->generate(
            $this->argument('module'),
            $this->argument('name')
        );

        $this->info('Entity created');

        return self::SUCCESS;
    }
}
