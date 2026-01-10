<?php

namespace Fixik\DddGenerator\Tests\Presets;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;

class CorePresetTest extends TestCase
{
    public function test_core_preset_generates_domain_only(): void
    {
        $this->artisan('ddd:make', [
            'preset' => 'domain',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $basePath = PathResolver::modulePath('Order');

        $this->assertFileExists("{$basePath}/Domain/Entities/Order.php");
        $this->assertFileExists("{$basePath}/OrderServiceProvider.php");
    }
}
