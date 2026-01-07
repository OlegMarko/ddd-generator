<?php

namespace Fixik\DddGenerator\Presets;

interface PresetInterface
{
    public function generate(string $module, string $entity): void;
}
