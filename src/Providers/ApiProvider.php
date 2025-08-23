<?php

/**
 * Service Provider responsible to register all REST endpoints
 *
 * @license MIT
 */

declare(strict_types=1);

namespace MyPlugin\Providers;

use Attributes\Wp\FastEndpoints\Router;

/**
 * Class responsible to register all REST FastEndpoint router's
 *
 * @author André Gil <andre_gil22@hotmail.com>
 */
class ApiProvider implements ProviderContract
{
    protected Router $appRouter;

    /**
     * Includes all sub-routers into $appRouter and then register's it
     */
    public function register(): void
    {
        $this->appRouter = new Router('my-plugin', 'v1');
        foreach (glob(\ROUTERS_DIR.'/*.php') as $filename) {
            $router = require $filename;
            $this->appRouter->includeRouter($router);
        }
        $this->appRouter->register();
    }
}
