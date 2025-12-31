<?php

namespace Fixik\DddGenerator\Tests\Commands;

use Fixik\DddGenerator\Support\EventListenerDiscovery;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class MegaCommandTest extends TestCase
{
    public function test_it_generates_full_ddd_module(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'module' => $module,
            '--entity' => 'Order',
            '--value-object' => ['OrderId'],
            '--event' => ['OrderCreated'],
            '--listener' => ['OrderCreated'],
            '--dto' => ['CreateOrderData'],
            '--repository' => true,
            '--usecase' => 'CreateOrder',
            '--model' => true,
            '--mapper' => true,
            '--cqrs' => true,
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);

        $this->assertFileExists("{$basePath}/Domain/Entities/Order.php");
        $this->assertFileExists("{$basePath}/Domain/ValueObjects/OrderId.php");
        $this->assertFileExists("{$basePath}/Domain/Events/OrderCreated.php");
        $this->assertFileExists("{$basePath}/Application/Listeners/OrderCreatedListener.php");
        $this->assertFileExists("{$basePath}/Application/DTO/CreateOrderData.php");
        $this->assertFileExists("{$basePath}/Domain/Repositories/OrderRepository.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Persistence/Repositories/EloquentOrderRepository.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Persistence/Models/{$module}.php");
        $this->assertFileExists("{$basePath}/Infrastructure/Persistence/Mappers/OrderMapper.php");
        $this->assertFileExists("{$basePath}/Application/UseCases/CreateOrder.php");
        $this->assertFileExists("{$basePath}/Application/Commands/CreateOrderCommand.php");
        $this->assertFileExists("{$basePath}/Application/CommandHandlers/CreateOrderHandler.php");
        $this->assertFileExists("{$basePath}/Application/Queries/GetOrderQuery.php");
        $this->assertFileExists("{$basePath}/Application/QueryHandlers/GetOrderHandler.php");
        $this->assertFileExists("{$basePath}/{$module}ServiceProvider.php");
    }

    public function test_generated_files_have_correct_namespaces(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'module' => $module,
            '--entity' => 'Order',
            '--event' => ['OrderCreated'],
            '--listener' => ['OrderCreated'],
            '--repository' => true,
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);
        $namespace = "App\\Modules\\{$module}";

        // Check entity namespace
        $entityContent = File::get("{$basePath}/Domain/Entities/Order.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Entities", $entityContent);

        // Check event namespace
        $eventContent = File::get("{$basePath}/Domain/Events/OrderCreated.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Events", $eventContent);

        // Check listener namespace
        $listenerContent = File::get("{$basePath}/Application/Listeners/OrderCreatedListener.php");
        $this->assertStringContainsString("namespace {$namespace}\\Application\\Listeners", $listenerContent);
        $this->assertStringContainsString("use {$namespace}\\Domain\\Events\\OrderCreated", $listenerContent);

        // Check repository namespace
        $repositoryContent = File::get("{$basePath}/Domain/Repositories/OrderRepository.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Repositories", $repositoryContent);

        // Check service provider namespace
        $providerContent = File::get("{$basePath}/{$module}ServiceProvider.php");
        $this->assertStringContainsString("namespace {$namespace}", $providerContent);
    }

    public function test_event_listeners_are_auto_registered(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make', [
            'module' => $module,
            '--event' => ['OrderCreated'],
            '--listener' => ['OrderCreated'],
        ])->assertExitCode(0);

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
            'module' => $module,
            '--entity' => 'Order',
            '--event' => ['OrderCreated'],
            '--listener' => ['OrderCreated'],
        ])->assertExitCode(0);

        $this->artisan('ddd:modules-cache')->assertExitCode(0);
        $this->artisan('ddd:event-listeners-cache')->assertExitCode(0);
    }
}
