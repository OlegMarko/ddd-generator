<?php

namespace Fixik\DddGenerator\Tests\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ServiceProviderRepositoryBindingTest extends TestCase
{
    public function test_repository_binding_is_idempotent(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make-module', [
            'name' => $module,
        ])->assertExitCode(0);

        $this->artisan('ddd:make-repository', [
            'module' => $module,
            'entity' => 'Order',
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/{$module}ServiceProvider.php";

        $content = File::get($path);

        $this->assertSame(
            1,
            substr_count($content, '$this->app->bind(')
        );

        $this->assertSame(
            1,
            substr_count(
                $content,
                "OrderRepository::class,\n            EloquentOrderRepository::class"
            )
        );

        $this->artisan('ddd:make-repository', [
            'module' => $module,
            'entity' => 'Order',
        ])->assertExitCode(0);

        $contentAfter = File::get($path);

        $this->assertSame(
            1,
            substr_count($content, '$this->app->bind(')
        );

        $this->assertSame(
            1,
            substr_count(
                $content,
                "OrderRepository::class,\n            EloquentOrderRepository::class"
            )
        );
    }
}
