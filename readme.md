# WordPress Plugin Base

Provides base class word plugins (and themes) to register hooks and actions using PHPs reflection API and PHP8 attributes.

## Requirements

- PHP >= 8.1

## Installation

```
composer require crtl/wp-plugin-base
```

## Usage

```php
<?php

use Crtl\WpPluginBase\PluginBase;
use Crtl\WpPluginBase\Attribute\WPAction;
use Crtl\WpPluginBase\Attribute\WPFilter;

class MyPlugin extends PluginBase {

    /**
     * Registers an action by creating method in format action_{action_name}
    * @return void
     */
    public function action_wp_enqueue_scripts() {
        wp_enqueue_script(...)
    }
    
    /**
     * Registers a filter by creating method in format action_{action_name} 
     * @return false
     */
    public function filter_admin_bar() {
        return false;
    }
    
    /**
     * Register action using {@link WPAction} attribute
     * @return void
     */
    #[WPAction("action_name", 10, 0)]
    public function usingAttributes() {
    
    }

    /**
     * Register filter using {@link WPFilter} attribute
     * @return void
     */
    #[WPFilter("filter_name", 10, 0)]
    public function usingAttributes() {
    
    }

}

```
> `WPFilter`, `WPAction` attributes can also ba used to set optional priority and args count for actions and filters respectively.
> ```php
> #[WPAction(priority: 10)]
> public function action_my_action() {}
> ```