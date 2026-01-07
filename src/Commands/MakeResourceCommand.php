<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\ResourceGenerator;
use Illuminate\Console\Command;

class MakeResourceCommand extends Command
{
    protected $signature = 'ddd:make-resource {module} {name}';
    protected $description = 'Create API Resource';

    public function handle(ResourceGenerator $generator): int
    {
        $generator->generate(
            module: $this->argument('module'),
            name: $this->argument('name')
        );

        $this->info('API Resource created');

        return self::SUCCESS;
    }
}
