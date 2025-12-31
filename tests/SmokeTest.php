<?php

namespace Fixik\DddGenerator\Tests;

use Fixik\DddGenerator\DddGeneratorServiceProvider;

class SmokeTest extends TestCase
{
    public function test_package_boots_without_errors(): void
    {
        // Verify the service provider is loaded
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(DddGeneratorServiceProvider::class, $providers);
        
        // Verify the app boots without errors
        $this->assertNotNull($this->app);
    }

    public function test_service_provider_is_registered(): void
    {
        $providers = $this->app->getLoadedProviders();
        
        $this->assertArrayHasKey(DddGeneratorServiceProvider::class, $providers);
    }
}
