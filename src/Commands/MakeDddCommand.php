<?php

namespace Fixik\DddGenerator\Commands;

use Fixik\DddGenerator\Presets\PresetResolver;
use Illuminate\Console\Command;
use InvalidArgumentException;

class MakeDddCommand extends Command
{
    protected $signature = 'ddd:make
        {preset : domain|http-crud|http-api}
        {module : Module name}
        {--entity= : Root entity name}
        {--style= : Generation style (e.g. cqrs)}';

    protected $description = 'Generate DDD module using presets';

    public function handle(PresetResolver $resolver): int
    {
        $preset = $this->argument('preset');
        $module = ucfirst($this->argument('module'));
        $entity = ucfirst($this->option('entity') ?? $module);
        $style = $this->option('style');

        if ($style) {
            $this->info("Applying preset [$preset] with style [$style] to module [$module]");
        } else {
            $this->info("Applying preset [$preset] to module [$module]");
        }

        try {
            $resolver->resolve($preset, $style)->generate($module, $entity);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->info("Preset [$preset] applied successfully ✅");

        return self::SUCCESS;
    }
}
