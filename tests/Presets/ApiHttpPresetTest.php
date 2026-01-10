<?php

namespace Fixik\DddGenerator\Tests\Presets;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ApiHttpPresetTest extends TestCase
{
    public function test_api_http_preset_generates_full_stack(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-cqrs',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $this->assertFileExists("{$basePath}/Application/Commands/CreateOrderCommand.php");
        $this->assertFileExists("{$basePath}/Application/CommandHandlers/CreateOrderHandler.php");

        $this->assertFileExists("{$basePath}/Application/Queries/GetOrderQuery.php");
        $this->assertFileExists("{$basePath}/Application/QueryHandlers/GetOrderHandler.php");

        $this->assertFileExists("{$basePath}/Domain/Repositories/OrderRepository.php");
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Persistence/Repositories/EloquentOrderRepository.php"
        );

        $this->assertFileExists(
            "{$basePath}/Infrastructure/Http/Controllers/OrderController.php"
        );
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Http/Requests/StoreOrderRequest.php"
        );
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Http/Resources/OrderResource.php"
        );
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Http/routes.php"
        );

        $this->assertFileExists(
            "{$basePath}/OrderServiceProvider.php"
        );
    }

    public function test_usecase_and_commands_do_not_depend_on_repository(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-cqrs',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $command = File::get(
            "{$basePath}/Application/Commands/CreateOrderCommand.php"
        );

        $this->assertStringNotContainsString(
            'Repository',
            $command
        );
    }

    public function test_handlers_depend_on_repository(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-cqrs',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $handler = File::get(
            "{$basePath}/Application/CommandHandlers/CreateOrderHandler.php"
        );

        $this->assertStringContainsString(
            'OrderRepository',
            $handler
        );
    }
}
