<?php

/**
 * Holds interface for Service Providers
 *
 * @license MIT
 */

namespace MyPlugin\Providers;

interface ProviderContract
{
    public function register(): void;
}
