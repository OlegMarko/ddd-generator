<?php

namespace Fixik\DddGenerator\Tests\Commands;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeEntityCommandTest extends TestCase
{
    public function test_it_creates_domain_entity(): void
    {
        $module = 'Order';
        $entity = 'Order';
        $path = PathResolver::modulePath($module) . "/Domain/Entities/{$entity}.php";

        $this->artisan('ddd:make-entity', [
            'module' => $module,
            'name' => $entity,
        ])->assertExitCode(0);

        $this->assertFileExists($path);
        
        $content = File::get($path);
        $this->assertStringContainsString('namespace App\\Modules\\Order\\Domain\\Entities', $content);
        $this->assertStringContainsString("class {$entity}", $content);
    }

    public function test_it_creates_entity_with_correct_namespace(): void
    {
        $module = 'Product';
        $entity = 'Product';
        
        $this->artisan('ddd:make-entity', [
            'module' => $module,
            'name' => $entity,
        ])->assertExitCode(0);

        $path = PathResolver::modulePath($module) . "/Domain/Entities/{$entity}.php";
        $content = File::get($path);
        
        $this->assertStringContainsString('namespace App\\Modules\\Product\\Domain\\Entities', $content);
    }
}
