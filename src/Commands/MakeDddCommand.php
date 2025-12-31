<?php

namespace Fixik\DddGenerator\Commands;

use Illuminate\Console\Command;

class MakeDddCommand extends Command
{
    protected $signature = 'ddd:make
        {module : Module name}

        {--entity= : Domain Entity}

        {--value-object=* : Domain Value Objects}
        {--event=* : Domain Events}

        {--listener=* : Event listeners (without Listener suffix)}
        {--queued-listener : Make listeners queueable}

        {--dto=* : Application DTOs}
        {--usecase= : Application UseCase}

        {--repository : Generate repository}
        {--model : Generate Eloquent model}
        {--mapper : Generate Entity ↔ Model mapper}

        {--cqrs : Enable CQRS (Command / Query)}';

    protected $description = 'Generate full DDD module structure';

    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));

        $this->info("Creating DDD module: {$module}");

        $this->callSilent('ddd:make-module', [
            'name' => $module,
            '--cqrs' => $this->option('cqrs'),
        ]);

        if ($entity = $this->option('entity')) {
            $entity = ucfirst($entity);
            $this->call('ddd:make-entity', [
                'module' => $module,
                'name' => $entity,
            ]);
        }

        foreach ($this->option('value-object') as $vo) {
            $this->call('ddd:make-value-object', [
                'module' => $module,
                'name' => ucfirst($vo),
            ]);
        }

        foreach ($this->option('event') as $event) {
            $this->call('ddd:make-event', [
                'module' => $module,
                'name' => ucfirst($event),
            ]);
        }

        foreach ($this->option('listener') as $listener) {
            foreach ($this->option('event') as $event) {
                $this->call('ddd:make-listener', [
                    'module'   => $module,
                    'event'    => ucfirst($event),
                    'listener' => ucfirst($listener),
                    '--queued' => $this->option('queued-listener'),
                ]);
            }
        }

        foreach ($this->option('dto') as $dto) {
            $this->call('ddd:make-dto', [
                'module' => $module,
                'name' => ucfirst($dto),
            ]);
        }

        if ($this->option('repository') && $entity) {
            $this->call('ddd:make-repository', [
                'module' => $module,
                'entity' => $entity,
            ]);
        }

        if ($this->option('model') && $entity) {
            $this->call('ddd:make-model', [
                'module' => $module,
                'name' => $entity,
            ]);
        }

        if ($this->option('mapper') && $entity) {
            $this->call('ddd:make-mapper', [
                'module' => $module,
                'entity' => $entity,
            ]);
        }

        if ($useCase = $this->option('usecase')) {
            $useCase = ucfirst($useCase);
            $this->call('ddd:make-usecase', [
                'module' => $module,
                'name' => $useCase,
                '--entity' => $entity,
            ]);
        }

        if ($this->option('cqrs') && $useCase) {
            $this->call('ddd:make-command', [
                'module' => $module,
                'name' => $useCase,
            ]);

            if ($entity) {
                $this->call('ddd:make-query', [
                    'module' => $module,
                    'name'   => 'Get' . $entity,
                ]);
            }
        }

        $this->newLine();
        $this->info("DDD module $module generated successfully ✅");

        return self::SUCCESS;
    }
}
