<?php

namespace Fixik\DddGenerator\Tests\Feature;

use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeRequestTest extends TestCase
{
    protected string $module = 'Order';

    /** @test */
    public function test_it_generates_form_request()
    {
        $this->artisan('ddd:make-request', [
            'module' => $this->module,
            'name'   => 'StoreOrderRequest',
        ])->assertExitCode(0);

        $path = app_path(
            'Modules/Order/Infrastructure/Http/Requests/StoreOrderRequest.php'
        );

        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString(
            'class StoreOrderRequest extends FormRequest',
            $content
        );

        $this->assertStringContainsString(
            'public function rules',
            $content
        );
    }
}
