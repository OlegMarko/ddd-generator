<?php

namespace Fixik\DddGenerator\Tests\Presets;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class CrudPresetTest extends TestCase
{
    public function test_crud_preset_generates_http_stack(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-crud',
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

        $controller = File::get(
            "{$basePath}/Infrastructure/Http/Controllers/OrderController.php"
        );
        $request = File::get(
            "{$basePath}/Infrastructure/Http/Requests/StoreOrderRequest.php"
        );
        $resource = File::get(
            "{$basePath}/Infrastructure/Http/Resources/OrderResource.php"
        );
        $routes = File::get(
            "{$basePath}/Infrastructure/Http/routes.php"
        );

        $this->assertStringContainsString(
            'namespace App\\Modules\\Order\\Infrastructure\\Http\\Controllers;',
            $controller
        );
        $this->assertStringContainsString(
            'namespace App\\Modules\\Order\\Infrastructure\\Http\\Requests;',
            $request
        );
        $this->assertStringContainsString(
            'namespace App\\Modules\\Order\\Infrastructure\\Http\\Resources;',
            $resource
        );

        $this->assertStringContainsString('public function index', $controller);
        $this->assertStringContainsString('public function store', $controller);
        $this->assertStringContainsString('public function show', $controller);
        $this->assertStringContainsString('public function update', $controller);
        $this->assertStringContainsString('public function destroy', $controller);

        $this->assertStringContainsString("Route::get('orders'", $routes);
        $this->assertStringContainsString("Route::post('orders'", $routes);
        $this->assertStringContainsString("Route::get('orders/{id}'", $routes);
        $this->assertStringContainsString("Route::put('orders/{id}'", $routes);
        $this->assertStringContainsString("Route::delete('orders/{id}'", $routes);
    }

    public function test_crud_preset_does_not_generate_cqrs_files(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-crud',
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
}
