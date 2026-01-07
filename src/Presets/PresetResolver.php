<?php

namespace Fixik\DddGenerator\Presets;

use InvalidArgumentException;

final class PresetResolver
{
    public function resolve(string $preset): PresetInterface
    {
        return match ($preset) {
            'api'  => new ApiPreset(),
            'api-http' => new ApiHttpPreset(),
            'core' => new CorePreset(),
            'crud' => new CrudPreset(),
            default => throw new InvalidArgumentException(
                "Unknown preset [$preset]"
            ),
        };
    }
}
