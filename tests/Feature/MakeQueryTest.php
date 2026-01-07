<?php

namespace Fixik\DddGenerator\Tests\Feature;

use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeQueryTest extends TestCase
{
    /** @test */
    public function test_it_generates_query_and_handler_with_correct_responsibilities(): void
    {
        $this->artisan('ddd:make-cqrs-query', [
            'module' => 'Order',
            'name'   => 'GetOrder',
            '--entity' => 'Order',
        ])->assertExitCode(0);

        $queryPath = app_path(
            'Modules/Order/Application/Queries/GetOrderQuery.php'
        );

        $handlerPath = app_path(
            'Modules/Order/Application/QueryHandlers/GetOrderHandler.php'
        );

        $this->assertFileExists($queryPath);
        $this->assertFileExists($handlerPath);

        $queryContent = File::get($queryPath);
        $handlerContent = File::get($handlerPath);

        $this->assertStringContainsString(
            'class GetOrder',
            $queryContent
        );

        $this->assertStringContainsString(
            'public readonly string $id',
            $queryContent
        );

        $this->assertStringNotContainsString(
            'Repository',
            $queryContent
        );

        $this->assertStringContainsString(
            'class GetOrderHandler',
            $handlerContent
        );

        $this->assertStringContainsString(
            'OrderRepository',
            $handlerContent
        );
    }
}
