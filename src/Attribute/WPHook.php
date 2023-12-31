<?php

namespace Crtl\WpPluginBase\Attribute;

abstract class WPHook
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $priority = 10,
        public readonly ?int $numArgs = 1,
    )
    {
    }
}