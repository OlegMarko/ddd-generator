<?php

namespace Fixik\DddGenerator\Presets;

use InvalidArgumentException;

final class PresetResolver
{
    public function resolve(string $preset, ?string $style = null): PresetInterface
    {
        $preset = strtolower($preset);
        $style = $style !== null ? strtolower($style) : null;

        if ($style !== null && $style !== 'cqrs') {
            throw new InvalidArgumentException("Unknown style [$style]");
        }

        if ($style === 'cqrs' && $preset === 'http-crud') {
            throw new InvalidArgumentException(
                "Style [$style] is not supported for preset [$preset]"
            );
        }

        return match ($preset) {
            'domain' => $style === 'cqrs' ? new ApiPreset() : new CorePreset(),
            'http-api' => $style === 'cqrs' ? new ApiHttpCqrsPreset() : new ApiHttpPreset(),
            'http-crud' => new CrudPreset(),
            default => throw new InvalidArgumentException(
                "Unknown preset [$preset]"
            ),
        };
    }
}
