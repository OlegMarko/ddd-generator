<?php

namespace Fixik\DddGenerator\Tests\Commands;

use Fixik\DddGenerator\Support\EventListenerDiscovery;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class MegaCommandTest extends TestCase
{
    public function test_it_generates_core_domain_module(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'preset' => 'domain',
            'module' => $module,
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);

        $this->assertFileExists("{$basePath}/Domain/Entities/Order.php");
        $this->assertFileExists("{$basePath}/{$module}ServiceProvider.php");
    }

    public function test_it_generates_api_http_stack(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => $module,
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);

        $this->assertFileExists("{$basePath}/Infrastructure/Http/Controllers/OrderController.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Http/Requests/StoreOrderRequest.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Http/Resources/OrderResource.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Http/routes.php");
    }

    public function test_it_generates_api_http_cqrs_stack(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => $module,
            '--entity' => 'Order',
            '--style' => 'cqrs',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);

        $this->assertFileExists("{$basePath}/Application/Commands/CreateOrderCommand.php");
        $this->assertFileExists("{$basePath}/Application/CommandHandlers/CreateOrderHandler.php");
        $this->assertFileExists("{$basePath}/Application/Queries/GetOrderQuery.php");
        $this->assertFileExists("{$basePath}/Application/QueryHandlers/GetOrderHandler.php");
    }

    public function test_generated_files_have_correct_namespaces(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => $module,
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);
        $namespace = "App\\Modules\\{$module}";

        $controller = File::get(
            "{$basePath}/Infrastructure/Http/Controllers/OrderController.php"
        );

        $this->assertStringContainsString(
            "namespace {$namespace}\\Infrastructure\\Http\\Controllers",
            $controller
        );

        $request = File::get(
            "{$basePath}/Infrastructure/Http/Requests/StoreOrderRequest.php"
        );

        $this->assertStringContainsString(
            "namespace {$namespace}\\Infrastructure\\Http\\Requests",
            $request
        );
    }

    public function test_event_listeners_are_auto_registered(): void
    {
        $this->artisan('ddd:make-event', [
            'module' => 'Order',
            'name' => 'OrderCreated',
        ]);

        $this->artisan('ddd:make-listener', [
            'module' => 'Order',
            'event' => 'OrderCreated',
            'listener' => 'OrderCreated',
        ]);

        Event::fake();
        EventListenerDiscovery::register();

        Event::assertListening(
            \App\Modules\Order\Domain\Events\OrderCreated::class,
            \App\Modules\Order\Application\Listeners\OrderCreatedListener::class
        );
    }

    public function test_cache_commands_work(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'preset' => 'domain',
            'module' => $module,
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $this->artisan('ddd:modules-cache')->assertExitCode(0);
        $this->artisan('ddd:event-listeners-cache')->assertExitCode(0);
    }
}
