<?php

namespace Fixik\DddGenerator\Tests\Support;

use Fixik\DddGenerator\Support\EventListenerDiscovery;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class EventListenerDiscoveryTest extends TestCase
{
    public function test_it_resolves_event_from_listener_name(): void
    {
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        // Create necessary directories
        File::makeDirectory("{$modulePath}/Application/Listeners", 0755, true);
        File::makeDirectory("{$modulePath}/Domain/Events", 0755, true);
        
        // Create event
        File::put(
            "{$modulePath}/Domain/Events/OrderCreated.php",
            "<?php namespace App\\Modules\\{$module}\\Domain\\Events; class OrderCreated {}"
        );
        
        // Create listener
        File::put(
            "{$modulePath}/Application/Listeners/OrderCreatedListener.php",
            "<?php namespace App\\Modules\\{$module}\\Application\\Listeners; use App\\Modules\\{$module}\\Domain\\Events\\OrderCreated; class OrderCreatedListener { public function handle(OrderCreated \$event) {} }"
        );

        Event::fake();

        EventListenerDiscovery::register();

        Event::assertListening(
            \App\Modules\Order\Domain\Events\OrderCreated::class,
            \App\Modules\Order\Application\Listeners\OrderCreatedListener::class
        );
    }

    public function test_it_returns_empty_map_when_no_listeners_exist(): void
    {
        $map = EventListenerDiscovery::rebuildCache();

        $this->assertIsArray($map);
        $this->assertEmpty($map);
    }

    public function test_it_skips_listeners_without_listener_suffix(): void
    {
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        File::makeDirectory("{$modulePath}/Application/Listeners", 0755, true);
        
        // Create listener without "Listener" suffix
        File::put(
            "{$modulePath}/Application/Listeners/SomeHandler.php",
            "<?php namespace App\\Modules\\{$module}\\Application\\Listeners; class SomeHandler {}"
        );

        $map = EventListenerDiscovery::rebuildCache();

        $this->assertEmpty($map);
    }

    public function test_it_skips_listeners_when_event_does_not_exist(): void
    {
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        File::makeDirectory("{$modulePath}/Application/Listeners", 0755, true);
        
        // Create listener but no corresponding event
        File::put(
            "{$modulePath}/Application/Listeners/NonExistentEventListener.php",
            "<?php namespace App\\Modules\\{$module}\\Application\\Listeners; class NonExistentEventListener {}"
        );

        $map = EventListenerDiscovery::rebuildCache();

        $this->assertEmpty($map);
    }
}

