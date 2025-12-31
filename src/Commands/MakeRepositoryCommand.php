<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\RepositoryGenerator;
use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'ddd:make-repository {module} {entity}';
    protected $description = 'Create domain repository interface and infrastructure implementation';

    public function handle(RepositoryGenerator $generator): int
    {
        $generator->generate(
            $this->argument('module'),
            $this->argument('entity')
        );

        $this->info('Repository created');

        return self::SUCCESS;
    }
}
