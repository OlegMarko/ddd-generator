<?php

namespace Fixik\DddGenerator\Presets;

use InvalidArgumentException;

final class PresetResolver
{
    public function resolve(string $preset): PresetInterface
    {
        return match ($preset) {
            'cqrs'  => new ApiPreset(),
            'http-cqrs' => new ApiHttpPreset(),
            'domain' => new CorePreset(),
            'http' => new CrudPreset(),
            default => throw new InvalidArgumentException(
                "Unknown preset [$preset]"
            ),
        };
    }
}
