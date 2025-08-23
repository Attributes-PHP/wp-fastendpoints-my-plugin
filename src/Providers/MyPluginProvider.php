<?php

/**
 * Service Provider responsible to boot my plugin
 *
 * @license MIT
 */

declare(strict_types=1);

namespace MyPlugin\Providers;

/**
 * Class responsible to boot the whole plugin
 *
 * @author André Gil <andre_gil22@hotmail.com>
 */
class MyPluginProvider implements ProviderContract
{
    protected array $allProviders = [
        ApiProvider::class,
    ];

    public function register(): void
    {
        foreach ($this->allProviders as $provider) {
            $provider = new $provider;
            $provider->register();
        }
    }

    public function boot(): void
    {
        // Do something else
    }
}
