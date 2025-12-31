<?php

namespace Fixik\DddGenerator\Tests\Support;

use Fixik\DddGenerator\Support\NamespaceResolver;
use Fixik\DddGenerator\Tests\TestCase;

class NamespaceResolverTest extends TestCase
{
    public function test_it_resolves_base_namespace_from_config(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals('App\\Modules', NamespaceResolver::base());
    }

    public function test_it_resolves_module_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals('App\\Modules\\Order', NamespaceResolver::module('Order'));
    }

    public function test_it_resolves_entity_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals(
            'App\\Modules\\Order\\Domain\\Entities\\Order',
            NamespaceResolver::entity('Order', 'Order')
        );
    }

    public function test_it_resolves_repository_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals(
            'App\\Modules\\Order\\Domain\\Repositories\\OrderRepository',
            NamespaceResolver::repository('Order', 'OrderRepository')
        );
    }

    public function test_it_resolves_service_provider_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals(
            'App\\Modules\\Order\\OrderServiceProvider',
            NamespaceResolver::serviceProvider('Order')
        );
    }

    public function test_it_resolves_listener_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals(
            'App\\Modules\\Order\\Application\\Listeners\\OrderCreatedListener',
            NamespaceResolver::listener('Order', 'OrderCreatedListener')
        );
    }

    public function test_it_resolves_event_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'App\\Modules');
        
        $this->assertEquals(
            'App\\Modules\\Order\\Domain\\Events\\OrderCreated',
            NamespaceResolver::event('Order', 'OrderCreated')
        );
    }

    public function test_it_works_with_custom_namespace(): void
    {
        $this->app['config']->set('ddd.base_namespace', 'Domain\\Modules');
        
        $this->assertEquals('Domain\\Modules', NamespaceResolver::base());
        $this->assertEquals('Domain\\Modules\\Order', NamespaceResolver::module('Order'));
        $this->assertEquals(
            'Domain\\Modules\\Order\\Domain\\Entities\\Order',
            NamespaceResolver::entity('Order', 'Order')
        );
    }
}

