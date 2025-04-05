<?php
spl_autoload_register(function ($class) {
    if (strpos($class, 'GRE_') === 0) {
        $file = plugin_dir_path(__FILE__) . 'class-' . strtolower(str_replace('GRE_', '', $class)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
