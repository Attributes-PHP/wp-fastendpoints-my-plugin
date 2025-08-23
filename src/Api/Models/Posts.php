<?php

/**
 * Holds validation models for the blog posts endpoints
 */

namespace MyPlugin\Api\Models;

use Attributes\Options\AliasGenerator;
use Attributes\Serialization\SerializableTrait;
use Respect\Validation\Rules;

enum Status: string
{
    case PUBLISH = 'publish';
    case DRAFT = 'draft';
    case PRIVATE = 'private';
}

#[AliasGenerator('snake')]
class Post
{
    use SerializableTrait;

    #[Rules\Positive]
    public int $postAuthor;

    public string $postTitle;

    public Status $postStatus;
}
