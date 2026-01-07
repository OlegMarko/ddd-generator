<?php

namespace Fixik\DddGenerator\Tests\Feature;

use Fixik\DddGenerator\Tests\TestCase;

class MakeControllerTest extends TestCase
{
    protected string $module = 'Order';

    /** @test */
    public function test_it_generates_http_controller()
    {
        $this->artisan('ddd:make-controller', [
            'module' => $this->module,
            'name' => 'OrderController',
        ])->assertExitCode(0);

        $path = app_path(
            'Modules/Order/Infrastructure/Http/Controllers/OrderController.php'
        );

        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString(
            'class OrderController extends Controller',
            $content
        );

        $this->assertStringContainsString(
            'public function store',
            $content
        );

        $this->assertStringContainsString(
            'public function show',
            $content
        );
    }
}
