<?php

namespace Fixik\DddGenerator\Tests\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ServiceProviderGeneratorTest extends TestCase
{
    public function test_service_provider_is_generated_without_repository(): void
    {
        $module = 'Order';

        $this->artisan('ddd:make-module', [
            'name' => $module,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module)
            . "/{$module}ServiceProvider.php";

        $this->assertFileExists($path);

        $content = File::get($path);

        $this->assertStringContainsString(
            "class {$module}ServiceProvider extends ServiceProvider",
            $content
        );

        $this->assertStringNotContainsString(
            'bind(',
            $content
        );
    }
}
