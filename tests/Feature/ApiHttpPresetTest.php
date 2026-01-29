<?php

namespace Fixik\DddGenerator\Tests\Feature;

use Fixik\DddGenerator\Tests\TestCase;

class ApiHttpPresetTest extends TestCase
{
    /** @test */
    public function test_it_generates_full_api_http_stack()
    {
        $this->artisan('ddd:make', [
            'preset' => 'http-api',
            'module' => 'Order',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $this->assertFileExists(
            app_path('Modules/Order/Infrastructure/Http/Controllers/OrderController.php')
        );

        $this->assertFileExists(
            app_path('Modules/Order/Infrastructure/Http/Requests/StoreOrderRequest.php')
        );

        $this->assertFileExists(
            app_path('Modules/Order/Infrastructure/Http/Resources/OrderResource.php')
        );

        $this->assertFileExists(
            app_path('Modules/Order/Infrastructure/Http/routes.php')
        );

        $routes = file_get_contents(
            app_path('Modules/Order/Infrastructure/Http/routes.php')
        );

        $this->assertStringContainsString(
            "Route::post",
            $routes
        );

        $this->assertStringContainsString(
            "Route::get",
            $routes
        );
    }
}
