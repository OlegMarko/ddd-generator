<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Illuminate\Support\Facades\File;

class ModuleGenerator
{
    public function generate(string $name, bool $cqrs): void
    {
        $base = PathResolver::modulePath($name);

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

        if ($cqrs) {
            $folders = array_merge($folders, [
                'Application/Commands',
                'Application/CommandHandlers',
                'Application/Queries',
                'Application/QueryHandlers',
            ]);
        }

        foreach ($folders as $folder) {
            File::makeDirectory("$base/$folder", 0755, true);
        }
    }
}
