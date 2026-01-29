<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Generators\ControllerGenerator;
use Illuminate\Console\Command;

class MakeControllerCommand extends Command
{
    protected $signature = 'ddd:make-controller
        {module}
        {name}
        {--style= : Controller style (api|cqrs|crud)}';
    protected $description = 'Create API controller';

    public function handle(ControllerGenerator $generator): int
    {
        $style = $this->option('style');

        if ($style && !in_array(strtolower($style), ['api', 'cqrs', 'crud'], true)) {
            $this->error("Unknown style [$style]");
            return self::FAILURE;
        }

        $generator->generate(
            module: $this->argument('module'),
            name: $this->argument('name'),
            style: $style,
        );

        $this->info('Controller created');

        return self::SUCCESS;
    }
}
