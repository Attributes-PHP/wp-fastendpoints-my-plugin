<?php

/**
 * Holds integration tests for the Posts Router
 *
 * @license MIT
 */

declare(strict_types=1);

namespace MyPlugin\Tests\Integration;

use MyPlugin\Tests\Helpers;
use Yoast\WPTestUtils\WPIntegration\TestCase;

if (! Helpers::isIntegrationTest()) {
    return;
}

/*
 * We need to provide the base test class to every integration test.
 * This will enable us to use all the WordPress test goodies, such as
 * factories and proper test cleanup.
 */
uses(TestCase::class);

beforeEach(function () {
    parent::setUp();

    // Set up a REST server instance.
    global $wp_rest_server;

    $this->server = $wp_rest_server = new \WP_REST_Server;
    do_action('rest_api_init', $this->server);
});

afterEach(function () {
    global $wp_rest_server;
    $wp_rest_server = null;

    parent::tearDown();
});

test('REST API endpoints registered', function () {
    $routes = $this->server->get_routes();

    expect($routes)
        ->toBeArray()
        ->toHaveKeys([
            '/my-plugin/v1',
            '/my-plugin/v1/posts',
            '/my-plugin/v1/posts/(?P<ID>[\\d]+)',
        ])
        ->and($routes['/my-plugin/v1/posts'])
        ->toHaveCount(1)
        ->and($routes['/my-plugin/v1/posts/(?P<ID>[\\d]+)'])
        ->toHaveCount(2);
})->group('api', 'posts');

test('Create a new post', function () {
    // Create user with correct permissions
    $userId = $this::factory()->user->create();
    $user = get_user_by('id', $userId);
    $user->add_cap('publish_posts');
    // Make request as that user
    wp_set_current_user($userId);
    $request = new \WP_REST_Request('POST', '/my-plugin/v1/posts');
    $request->set_body_params([
        'post_title' => 'My testing message',
        'post_status' => 'publish',
        'post_author' => $userId,
    ]);
    $response = $this->server->dispatch($request);
    expect($response->get_status())->toBe(201);
    $postId = $response->get_data();
    // Check that the post details are correct
    expect(get_post($postId))
        ->toBeInstanceOf(\WP_Post::class)
        ->toHaveProperty('post_title', 'My testing message')
        ->toHaveProperty('post_status', 'publish')
        ->toHaveProperty('post_author', $userId);
})->group('api', 'posts');

test('Retrieves a post', function () {
    // Create user with correct permissions
    $userId = $this::factory()->user->create();
    $user = get_user_by('id', $userId);
    $user->add_cap('read');
    // Create post
    $postId = $this::factory()->post->create(['post_author' => $userId, 'post_title' => 'My testing message']);
    // Make request as that user
    wp_set_current_user($userId);
    $request = new \WP_REST_Request('GET', "/my-plugin/v1/posts/{$postId}");
    $response = $this->server->dispatch($request);
    // Ensures only the correct fields are returned
    expect($response->get_status())->toBe(200)
        ->and($response->get_data())
        ->toEqual([
            'post_title' => 'My testing message',
            'post_author' => $userId,
            'post_status' => 'publish',
        ]);
})->group('api', 'posts');

test('Delete a post', function () {
    // Create user with correct permissions
    $userId = $this::factory()->user->create();
    $user = get_user_by('id', $userId);
    $user->add_cap('delete_published_posts');
    // Create post
    $postId = $this::factory()->post->create(['post_author' => $userId]);
    // Make request as that user
    wp_set_current_user($userId);
    $request = new \WP_REST_Request('DELETE', "/my-plugin/v1/posts/{$postId}");
    $response = $this->server->dispatch($request);
    // Check that the post has been deleted
    expect($response->get_status())->toBe(200)
        ->and(get_post($postId))
        ->toHaveProperty('post_status', 'trash');
})->group('api', 'posts');

test('Trying to manipulate a post without permissions', function (string $method, string $route) {
    $request = new \WP_REST_Request($method, $route);
    $response = $this->server->dispatch($request);
    expect($response->get_status())->toBe(403);
})->with([
    ['POST', '/my-plugin/v1/posts'],
    ['GET', '/my-plugin/v1/posts/1'],
    ['DELETE', '/my-plugin/v1/posts/1'],
])->group('api', 'posts');
