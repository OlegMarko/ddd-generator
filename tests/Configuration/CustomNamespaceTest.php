<?php

namespace Fixik\DddGenerator\Tests\Configuration;

use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class CustomNamespaceTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        
        // Use custom namespace
        $app['config']->set('ddd.base_namespace', 'Domain\\Modules');
    }

    public function test_generated_files_use_custom_namespace(): void
    {
        $module = 'Order';
        $entity = 'Order';

        $this->artisan('ddd:make-entity', [
            'module' => $module,
            'name' => $entity,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Domain/Entities/{$entity}.php";
        $content = File::get($path);
        
        $this->assertStringContainsString('namespace Domain\\Modules\\Order\\Domain\\Entities', $content);
        $this->assertStringNotContainsString('App\\Modules', $content);
    }

    public function test_all_components_use_custom_namespace(): void
    {
        $module = 'Product';

        $this->artisan('ddd:make', [
            'module' => $module,
            '--entity' => 'Product',
            '--event' => ['ProductCreated'],
            '--listener' => ['ProductCreated'],
            '--repository' => true,
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath($module);
        $namespace = "Domain\\Modules\\{$module}";

        // Check entity
        $entityContent = File::get("{$basePath}/Domain/Entities/Product.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Entities", $entityContent);

        // Check event
        $eventContent = File::get("{$basePath}/Domain/Events/ProductCreated.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Events", $eventContent);

        // Check listener
        $listenerContent = File::get("{$basePath}/Application/Listeners/ProductCreatedListener.php");
        $this->assertStringContainsString("namespace {$namespace}\\Application\\Listeners", $listenerContent);
        $this->assertStringContainsString("use {$namespace}\\Domain\\Events\\ProductCreated", $listenerContent);

        // Check repository
        $repositoryContent = File::get("{$basePath}/Domain/Repositories/ProductRepository.php");
        $this->assertStringContainsString("namespace {$namespace}\\Domain\\Repositories", $repositoryContent);

        // Check service provider
        $providerContent = File::get("{$basePath}/{$module}ServiceProvider.php");
        $this->assertStringContainsString("namespace {$namespace}", $providerContent);
    }

    public function test_namespace_resolver_uses_custom_namespace(): void
    {
        $this->assertEquals('Domain\\Modules', NamespaceResolver::base());
        $this->assertEquals('Domain\\Modules\\Order', NamespaceResolver::module('Order'));
        $this->assertEquals(
            'Domain\\Modules\\Order\\Domain\\Entities\\Order',
            NamespaceResolver::entity('Order', 'Order')
        );
    }
}

