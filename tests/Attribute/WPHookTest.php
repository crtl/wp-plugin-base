<?php

namespace Crtl\WpPluginBase\Test\Attribute;

use Crtl\WpPluginBase\Attribute\WPAction;
use Crtl\WpPluginBase\Attribute\WPFilter;
use PHPUnit\Framework\TestCase;

class WPHookTest extends TestCase
{

    public function test_default_args()
    {
        $classes = [WPAction::class, WPFilter::class];

        foreach ($classes as $class) {
            $o = new $class("name");

            $this->assertEquals("name", $o->name);
            $this->assertEquals(10, $o->priority);
            $this->assertEquals(1, $o->numArgs);
        }


    }

    public function test_constructor_args() {
        $classes = [WPAction::class, WPFilter::class];

        foreach ($classes as $class) {
            $o = new $class("name", 9, 8);

            $this->assertEquals("name", $o->name);
            $this->assertEquals(9, $o->priority);
            $this->assertEquals(8, $o->numArgs);
        }
    }


}
