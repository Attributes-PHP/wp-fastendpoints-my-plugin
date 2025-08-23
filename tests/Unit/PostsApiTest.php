<?php

/**
 * Holds unit tests for testing the post router.
 *
 * @license MIT
 */

declare(strict_types=1);

namespace MyPlugin\Tests\Unit;

use Attributes\Wp\FastEndpoints\Endpoint;
use Attributes\Wp\FastEndpoints\Router;
use Mockery;
use MyPlugin\Api\Models\Post;

afterEach(function () {
    Mockery::close();
});

function assertCap(string $method, string $path, string|array $capabilities, $endpoint = null)
{
    $capabilities = is_string($capabilities) ? [$capabilities] : $capabilities;
    // Create endpoint mock
    $endpoint = $endpoint ?: Mockery::mock(Endpoint::class);
    $endpoint
        ->shouldReceive('hasCap')
        ->once()
        ->with(...$capabilities);
    // Create router
    $router = Mockery::mock(Router::class)
        ->shouldIgnoreMissing(Mockery::mock(Endpoint::class)->shouldIgnoreMissing(Mockery::self()));
    $router
        ->shouldReceive($method)
        ->once()
        ->with($path, Mockery::type('callable'))
        ->andReturn($endpoint);
    require \ROUTERS_DIR.'/Posts.php';
}

test('Check that we are returning a Router instance', function () {
    $router = require \ROUTERS_DIR.'/Posts.php';
    expect($router)->toBeInstanceOf(Router::class);
})->group('api', 'posts');

test('Create post has correct permissions', function () {
    assertCap('post', '/', 'publish_posts');
})->group('api', 'posts');

test('Retrieve post has correct permissions and response uses correct model', function () {
    $endpoint = Mockery::mock(Endpoint::class);
    $endpoint
        ->shouldReceive('returns')
        ->once()
        ->with(Post::class)
        ->andReturnSelf();
    assertCap('get', '(?P<ID>[\d]+)', 'read', $endpoint);
})->group('api', 'posts');

test('Deleting post endpoint has correct permissions and schema and response uses correct model', function () {
    $endpoint = Mockery::mock(Endpoint::class);
    $endpoint
        ->shouldReceive('returns')
        ->once()
        ->with(Post::class)
        ->andReturnSelf();
    assertCap('delete', '(?P<ID>[\d]+)', ['delete_post', '<ID>'], $endpoint);
})->group('api', 'posts');
