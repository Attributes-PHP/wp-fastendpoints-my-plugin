<?php

/*
Plugin Name: My Plugin
Plugin URI: https://github.com/matapatos/wp-fastendpoints/wiki/Quick-start
Description: Sample plugin that demonstrates how to use FastEndpoints
Version: 1.0
Author: matapatos
Author URI: https://github.com/matapatos
License: MIT
*/
$composer = __DIR__.'/vendor/autoload.php';
if (! file_exists($composer)) {
    wp_die(
        esc_html__(
            'Error locating autoloader in plugins/my-plugin. Please run <code>composer install</code>.',
            'my-plugin',
        ),
    );
}

require_once $composer;

$myPlugin = new \MyPlugin\Providers\MyPluginProvider;
$myPlugin->register();

add_action('init', [$myPlugin, 'boot']);
