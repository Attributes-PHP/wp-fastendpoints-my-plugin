<?php

/**
 * Holds REST endpoints to interact with blog posts
 *
 * @license MIT
 */

declare(strict_types=1);

namespace MyPlugin\Api\Routers;

use Attributes\Wp\FastEndpoints\Helpers\WpError;
use Attributes\Wp\FastEndpoints\Router;
use MyPlugin\Api\Models\Post;
use Respect\Validation\Rules;
use WP_REST_Response;

$router = $router ?? new Router('posts');

$router->post('/', function (Post $post, WP_REST_Response $response) {
    $response->set_status(201);
    $payload = $post->serialize();

    return wp_insert_post($payload, true);
})
    ->hasCap('publish_posts');

$router->get('(?P<ID>[\d]+)', function (#[Rules\Positive] int $ID) {
    $post = get_post($ID);

    return $post ?: new WpError(404, 'Post not found');
})
    ->returns(Post::class)
    ->hasCap('read');

$router->delete('(?P<ID>[\d]+)', function (#[Rules\Positive] int $ID) {
    return wp_delete_post($ID) ?: new WpError(500, 'Unable to delete post');
})
    ->returns(Post::class)
    ->hasCap('delete_post', '<ID>');

// IMPORTANT: If no service provider is used make sure to set a version to the $router and call
//            the following function here:
// $router->register();

// Used later on by the ApiProvider
return $router;
