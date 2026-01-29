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
            'preset' => 'http-api',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $this->assertFileExists("{$basePath}/Domain/Repositories/OrderRepository.php");
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Persistence/Repositories/EloquentOrderRepository.php"
        );
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Persistence/Models/Order.php"
        );
        $this->assertFileExists(
            "{$basePath}/Infrastructure/Persistence/Mappers/OrderMapper.php"
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

    public function test_api_http_preset_does_not_generate_cqrs_files(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $this->assertFileDoesNotExist(
            "{$basePath}/Application/Commands/CreateOrderCommand.php"
        );
        $this->assertFileDoesNotExist(
            "{$basePath}/Application/CommandHandlers/CreateOrderHandler.php"
        );
        $this->assertFileDoesNotExist(
            "{$basePath}/Application/Queries/GetOrderQuery.php"
        );
        $this->assertFileDoesNotExist(
            "{$basePath}/Application/QueryHandlers/GetOrderHandler.php"
        );
    }

    public function test_api_http_preset_with_cqrs_generates_full_stack(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => 'Order',
            '--entity' => 'Order',
            '--style' => 'cqrs',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $this->assertFileExists("{$basePath}/Application/Commands/CreateOrderCommand.php");
        $this->assertFileExists("{$basePath}/Application/CommandHandlers/CreateOrderHandler.php");
        $this->assertFileExists("{$basePath}/Application/Queries/GetOrderQuery.php");
        $this->assertFileExists("{$basePath}/Application/QueryHandlers/GetOrderHandler.php");

        $handler = File::get(
            "{$basePath}/Application/CommandHandlers/CreateOrderHandler.php"
        );

        $this->assertStringContainsString(
            'OrderRepository',
            $handler
        );
    }
}
