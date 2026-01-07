<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\RequestGenerator;
use Illuminate\Console\Command;

class MakeRequestCommand extends Command
{
    protected $signature = 'ddd:make-request {module} {name}';
    protected $description = 'Create FormRequest';

    public function handle(RequestGenerator $generator): int
    {
        $generator->generate(
            module: $this->argument('module'),
            name: $this->argument('name')
        );

        $this->info('FormRequest created');

        return self::SUCCESS;
    }
}
