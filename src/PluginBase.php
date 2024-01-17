<?php

namespace Crtl\WpPluginBase;

use Crtl\WpPluginBase\Attribute\WPAction;
use Crtl\WpPluginBase\Attribute\WPFilter;
use Crtl\WpPluginBase\Attribute\WPHook;
use ReflectionClass;

/**
 * Base class for WordPress plugins or themes which allows registering hooks through attributes or method names
 *
 * To register a WordPress hook or filter you can use one of the following methods:
 * 1. Prefix the method with `hook` or `filter` (eg: action_init, action_wp_enqueue_scripts, filter_admin_bar)
 *      - To define priority and arguments count use attributes
 * 2. Use attributes {@link WPFilter} or {@link WPAction}
 *
 */
abstract class PluginBase
{

    /**
     * Singleton plugin instance
     * @var static|null
     */
    protected static ?self $instance = null;

    /**
     * Indicates if hooks have been initialized
     * @var bool
     */
    protected bool $initialized = false;


    protected function __construct()
    {
        $this->initHooks();
    }

    /**
     * Registers hooks and filters if they have not been already registered
     * @return void
     */
    protected function initHooks(): void
    {
        // Prevent duplicate registration
        if ($this->initialized) return;
        $this->initialized = true;

        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getMethods() as $method) {
            $callable = [$this, $method->name];

            // Get attributes and filter for attributes implementing WPHook
            $attrs = array_filter(
                array_map(fn($a) => $a->newInstance(), $method->getAttributes()),
                fn($attr) => $attr instanceof WPHook
            );

            $pattern = "/^(filter|action)_*/";

            if (empty($attrs) && !preg_match($pattern, $method->name)) {
                continue;
            }


            if (empty($attrs)) {
                $args = [
                    preg_replace("/^(filter|action)_/", "", $method->name),
                    10,
                    count($method->getParameters())
                ];

                $attrs = [
                    str_starts_with("action_", $method->name) ? new WPAction(...$args) : new WPFilter(...$args)
                ];
            }

            $name = preg_replace("/^(filter|action)_/", "", $method->name);
            foreach ($attrs as $attr) {

                $fn = $attr instanceof WPAction ? "add_action" : "add_filter";
                call_user_func($fn, $attr->name ?? $name, $callable, $attr->priority, $attr->numArgs);
            }
        }
    }

    /**
     * @param ...$args
     * @return static
     */
    static public function init(...$args)
    {
        static $instances = [];
        $class = get_called_class();

        if (!isset($instances[$class])) {
            $instances[$class] = new $class(...$args);
        }
        return $instances[$class];
    }

}
