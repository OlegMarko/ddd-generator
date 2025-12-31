<?php

namespace Fixik\DddGenerator\Generators;

use Fixik\DddGenerator\Support\PathResolver;
use Fixik\DddGenerator\Support\StubRenderer;
use Illuminate\Support\Facades\File;

class CqrsGenerator
{
    public function command(string $module, string $name): void
    {
        $this->generate(
            module: $module,
            stub: 'cqrs/command.stub',
            path: "Application/Commands/{$name}Command.php",
            data: [
                'command' => "{$name}Command",
                'properties' => '',
            ]
        );
    }

    public function commandHandler(string $module, string $name): void
    {
        $this->generate(
            module: $module,
            stub: 'cqrs/command_handler.stub',
            path: "Application/CommandHandlers/{$name}Handler.php",
            data: [
                'command' => "{$name}Command",
                'handler' => "{$name}Handler",
            ]
        );
    }

    public function query(string $module, string $name): void
    {
        $this->generate(
            module: $module,
            stub: 'cqrs/query.stub',
            path: "Application/Queries/{$name}Query.php",
            data: [
                'query' => "{$name}Query",
                'properties' => '',
            ]
        );
    }

    public function queryHandler(string $module, string $name): void
    {
        $this->generate(
            module: $module,
            stub: 'cqrs/query_handler.stub',
            path: "Application/QueryHandlers/{$name}Handler.php",
            data: [
                'query' => "{$name}Query",
                'handler' => "{$name}Handler",
            ]
        );
    }

    private function generate(
        string $module,
        string $stub,
        string $path,
        array  $data
    ): void
    {
        $fullPath = PathResolver::modulePath($module) . "/$path";

        $content = StubRenderer::render(
            __DIR__ . "/../../stubs/$stub",
            array_merge(['module' => $module], $data)
        );

        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $content);
    }
}
