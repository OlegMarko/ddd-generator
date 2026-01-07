<?php

namespace Fixik\DddGenerator\Tests\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class IndividualCommandsTest extends TestCase
{
    public function test_make_value_object_command(): void
    {
        $module = 'Order';
        $name = 'OrderId';

        $this->artisan('ddd:make-value-object', [
            'module' => $module,
            'name' => $name,
            '--type' => 'string',
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Domain/ValueObjects/{$name}.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Domain\\ValueObjects', $content);
        $this->assertStringContainsString("final class {$name}", $content);
        $this->assertStringContainsString('private string $value', $content);
    }

    public function test_make_event_command(): void
    {
        $module = 'Order';
        $name = 'OrderCreated';

        $this->artisan('ddd:make-event', [
            'module' => $module,
            'name' => $name,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Domain/Events/{$name}.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Domain\\Events', $content);
        $this->assertStringContainsString("final class {$name}", $content);
    }

    public function test_make_dto_command(): void
    {
        $module = 'Order';
        $name = 'CreateOrderData';

        $this->artisan('ddd:make-dto', [
            'module' => $module,
            'name' => $name,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Application/DTO/{$name}.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\DTO', $content);
        $this->assertStringContainsString("final class {$name}", $content);
    }

    public function test_make_listener_command(): void
    {
        $module = 'Order';
        $event = 'OrderCreated';
        $listener = 'SendEmail';

        $this->artisan('ddd:make-listener', [
            'module' => $module,
            'event' => $event,
            'listener' => $listener,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Application/Listeners/{$listener}Listener.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\Listeners', $content);
        $this->assertStringContainsString("class {$listener}Listener", $content);
        $this->assertStringNotContainsString('ShouldQueue', $content); // Not queued by default
    }

    public function test_make_listener_command_with_queued_option(): void
    {
        $module = 'Order';
        $event = 'OrderCreated';
        $listener = 'SendEmail';

        $this->artisan('ddd:make-listener', [
            'module' => $module,
            'event' => $event,
            'listener' => $listener,
            '--queued' => true,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Application/Listeners/{$listener}Listener.php";
        
        $content = File::get($path);
        $this->assertStringContainsString('ShouldQueue', $content);
    }

    public function test_make_repository_command(): void
    {
        $module = 'Order';
        $entity = 'Order';

        $this->artisan('ddd:make-repository', [
            'module' => $module,
            'entity' => $entity,
        ])->assertExitCode(0);

        $interfacePath = PathResolver::modulePath($module) . "/Domain/Repositories/{$entity}Repository.php";
        $implementationPath = PathResolver::modulePath($module) . "/Infrastructure/Persistence/Repositories/Eloquent{$entity}Repository.php";
        
        $this->assertFileExists($interfacePath);
        $this->assertFileExists($implementationPath);
        
        $interfaceContent = File::get($interfacePath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Domain\\Repositories', $interfaceContent);
        $this->assertStringContainsString("interface {$entity}Repository", $interfaceContent);
        
        $implementationContent = File::get($implementationPath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Infrastructure\\Persistence\\Repositories', $implementationContent);
    }

    public function test_make_model_command(): void
    {
        $module = 'Order';
        $name = 'Order';

        $this->artisan('ddd:make-model', [
            'module' => $module,
            'name' => $name,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Infrastructure/Persistence/Models/{$name}.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Infrastructure\\Persistence\\Models', $content);
        $this->assertStringContainsString("class {$name}", $content);
        $this->assertStringContainsString("protected \$table = 'orders'", $content);
    }

    public function test_make_mapper_command(): void
    {
        $module = 'Order';
        $entity = 'Order';

        $this->artisan('ddd:make-mapper', [
            'module' => $module,
            'entity' => $entity,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Infrastructure/Persistence/Mappers/{$entity}Mapper.php";
        
        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Infrastructure\\Persistence\\Mappers', $content);
        $this->assertStringContainsString("final class {$entity}Mapper", $content);
    }

    public function test_make_usecase_command(): void
    {
        $this->artisan('ddd:make-usecase', [
            'module' => 'Order',
            'name' => 'CreateOrder',
        ])->assertExitCode(0);

        $path = app_path(
            'Modules/Order/Application/UseCases/CreateOrder.php'
        );

        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString(
            'class CreateOrder',
            $content
        );
        $this->assertStringContainsString(
            'public function execute',
            $content
        );
        $this->assertStringNotContainsString(
            'Repository',
            $content
        );
    }

    public function test_make_command_command(): void
    {
        $module = 'Order';
        $name = 'CreateOrder';

        $this->artisan('ddd:make-cqrs-command', [
            'module' => $module,
            'name' => $name,
            '--entity' => $module,
        ])->assertExitCode(0);

        $commandPath = PathResolver::modulePath($module) . "/Application/Commands/{$name}Command.php";
        $handlerPath = PathResolver::modulePath($module) . "/Application/CommandHandlers/{$name}Handler.php";
        
        $this->assertFileExists($commandPath);
        $this->assertFileExists($handlerPath);
        
        $commandContent = File::get($commandPath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\Commands', $commandContent);
        
        $handlerContent = File::get($handlerPath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\CommandHandlers', $handlerContent);
    }

    public function test_make_query_command(): void
    {
        $module = 'Order';
        $name = 'GetOrder';

        $this->artisan('ddd:make-cqrs-query', [
            'module' => $module,
            'name' => $name,
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $queryPath = PathResolver::modulePath($module) . "/Application/Queries/{$name}Query.php";
        $handlerPath = PathResolver::modulePath($module) . "/Application/QueryHandlers/{$name}Handler.php";
        
        $this->assertFileExists($queryPath);
        $this->assertFileExists($handlerPath);
        
        $queryContent = File::get($queryPath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\Queries', $queryContent);
        
        $handlerContent = File::get($handlerPath);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Application\\QueryHandlers', $handlerContent);
    }
}

