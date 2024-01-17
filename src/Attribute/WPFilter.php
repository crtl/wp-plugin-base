<?php

namespace Crtl\WpPluginBase\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::\IS_REPEATABLE)]
class WPFilter extends WPHook
{}
