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
            $pattern = sprintf(
                '/{{#%s}}(.*?){{\/%s}}/s',
                preg_quote($key, '/'),
                preg_quote($key, '/')
            );

            if (!empty($value)) {
                $content = preg_replace($pattern, '$1', $content);
            } else {
                $content = preg_replace($pattern, '', $content);
            }
        }

        foreach ($data as $key => $value) {
            $content = str_replace(
                "{{ $key }}",
                (string) ($value ?? ''),
                $content
            );
        }

        return preg_replace('/{{[^}]+}}/', '', $content);
    }
}