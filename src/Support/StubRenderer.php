<?php

namespace Fixik\DddGenerator\Support;

class StubRenderer
{
    public static function render(string $stub, array $data): string
    {
        $content = file_get_contents($stub);

        if (isset($data['module']) && !isset($data['namespace'])) {
            $data['namespace'] = NamespaceResolver::module($data['module']);
        }

        foreach ($data as $key => $value) {
            $content = str_replace("{{ $key }}", $value, $content);
        }

        return $content;
    }
}
