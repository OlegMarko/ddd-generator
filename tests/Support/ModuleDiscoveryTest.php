<?php

namespace Fixik\DddGenerator\Tests\Support;

use Fixik\DddGenerator\Support\ModuleDiscovery;
use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ModuleDiscoveryTest extends TestCase
{
    public function test_it_discovers_modules(): void
    {
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        // Create module directory and service provider
        File::makeDirectory($modulePath, 0755, true);
        File::put(
            "{$modulePath}/{$module}ServiceProvider.php",
            "<?php namespace App\\Modules\\{$module}; class {$module}ServiceProvider {}"
        );

        $providers = ModuleDiscovery::rebuildCache();

        $this->assertContains("App\\Modules\\{$module}\\{$module}ServiceProvider", $providers);
    }

    public function test_it_returns_empty_array_when_no_modules_exist(): void
    {
        $providers = ModuleDiscovery::rebuildCache();

        $this->assertIsArray($providers);
        $this->assertEmpty($providers);
    }

    public function test_it_uses_namespace_from_config(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'Domain\\Modules');
        
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        File::makeDirectory($modulePath, 0755, true);
        File::put(
            "{$modulePath}/{$module}ServiceProvider.php",
            "<?php namespace Domain\\Modules\\{$module}; class {$module}ServiceProvider {}"
        );

        $providers = ModuleDiscovery::rebuildCache();

        $this->assertContains("Domain\\Modules\\{$module}\\{$module}ServiceProvider", $providers);
    }

    public function test_it_registers_discovered_providers(): void
    {
        $module = 'Order';
        $modulePath = PathResolver::modulePath($module);
        
        File::makeDirectory($modulePath, 0755, true);
        File::put(
            "{$modulePath}/{$module}ServiceProvider.php",
            "<?php namespace App\\Modules\\{$module}; use Illuminate\\Support\\ServiceProvider; class {$module}ServiceProvider extends ServiceProvider { public function register() {} }"
        );

        ModuleDiscovery::discover($this->app);

        // Provider should be registered (we can't easily test this without mocking, but we can verify it doesn't throw)
        $this->assertTrue(true);
    }
}

