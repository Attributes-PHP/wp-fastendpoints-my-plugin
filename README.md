# Quick Start - WP-FastEndpoints

<p align="center">
    <a href="https://github.com/matapatos/wp-fastendpoints-my-plugin/actions"><img alt="GitHub Actions Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/matapatos/wp-fastendpoints-my-plugin/tests.yml"></a>
    <a href="https://packagist.org/packages/matapatos/wp-fastendpoints"><img alt="Supported WordPress Versions" src="https://img.shields.io/badge/6.x-versions?logo=wordpress&label=versions"></a>
    <a href="https://packagist.org/packages/matapatos/wp-fastendpoints"><img alt="Software License" src="https://img.shields.io/packagist/l/matapatos/wp-fastendpoints"></a>
</p>

**MyPlugin** is a WordPress sample plugin that demonstrates how to use FastEndpoints.

- Follow up our guide at **[Quick Start FastEndpoints Wiki »](https://github.com/attributes-php/wp-fastendpoints/wiki/Quick-start)**

## Features

- Sample router to manipulate blog posts (create, update, retrieve and delete)
- Out of the box Unit tests + Integration tests using [pestphp](https://pestphp.com/) thanks to [dingo-d/wp-pest](https://github.com/dingo-d/wp-pest)
- PHP code style fixer using [laravel/pint](https://github.com/laravel/pint)
- Composer scripts for running tests + linter + setting up WordPress

## Requirements

- PHP 8.1+
- WordPress 6.x
- [attributes-php/wp-fastendpoints](https://packagist.org/packages/attributes-php/wp-fastendpoints)

## Installation

Add plugin to WordPress and then install all the dependencies:

```bash
composer install
```

## Lint

```bash
composer test:lint
```

## Running tests

```bash
composer test             # Runs linter + unit and integration tests
composer test:unit        # Runs unit tests
composer test:integration # Runs integration tests
```

### Setup WordPress

Please note that before running the integration tests you have to specify the WordPress
version you want to use, via:

```bash
composer setup:wp:6.0     # For the latest 6.0.x version
composer setup:wp:6.1     # For the latest 6.1.x version
composer setup:wp:6.2     # For the latest 6.2.x version
composer setup:wp:6.3     # For the latest 6.3.x version
composer setup:wp:6.4     # For the latest 6.4.x version
composer setup:wp:6.5     # For the latest 6.5.x version
composer setup:wp:6.6     # For the latest 6.6.x version
composer setup:wp:6.7     # For the latest 6.7.x version
composer setup:wp:latest  # For the latest x.x.x version
```

### Known errors

#### WordPress 6.5.x requires MySQL 5.5.5 or higher

Since WordPress have bumped the minimum MySQL version from 5.0 to 5.5.5 in WordPress
6.5.x the integration tests might not work for the latest version until
this [PR changes is released](https://github.com/aaemnnosttv/wp-sqlite-db/pull/61).
In other words, `composer setup:wp:latest` might not work so use `composer setup:wp:6.4`
instead. 

MyPlugin was created by **[André Gil](https://www.linkedin.com/in/andre-gil/)** and is open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
