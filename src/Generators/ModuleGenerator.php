<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Illuminate\Support\Facades\File;

final class ModuleGenerator
{
    public function __construct(
        private readonly ServiceProviderGenerator $serviceProviderGenerator
    ) {}

    public function generate(string $module, bool $cqrs = false): void
    {
        $this->createDirectories($module);

        $this->serviceProviderGenerator->generate($module);

        if ($cqrs) {
            $this->createCqrsStructure($module);
        }
    }

    private function createDirectories(string $module): void
    {
        $base = PathResolver::modulePath($module);

        $folders = [
            'Domain/Entities',
            'Domain/ValueObjects',
            'Domain/Repositories',
            'Domain/Events',
            'Application/UseCases',
            'Application/DTO',
            'Application/Listeners',
            'Infrastructure/Persistence/Models',
            'Infrastructure/Persistence/Repositories',
            'Infrastructure/Persistence/Mappers',
        ];

        foreach ($folders as $folder) {
            File::makeDirectory("$base/$folder", 0755, true);
        }
    }

    private function createCqrsStructure(string $module): void
    {
        $base = PathResolver::modulePath($module);

        $folders = [
            'Application/Commands',
            'Application/CommandHandlers',
            'Application/Queries',
            'Application/QueryHandlers',
        ];

        foreach ($folders as $folder) {
            File::makeDirectory("$base/$folder", 0755, true);
        }
    }
}
