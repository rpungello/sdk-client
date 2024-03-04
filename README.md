# Generic client for creating PHP-based SDKs using Spatie's DTOs for data transfer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rpungello/sdk-client.svg?style=flat-square)](https://packagist.org/packages/rpungello/sdk-client)
[![Tests](https://img.shields.io/github/actions/workflow/status/rpungello/sdk-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rpungello/sdk-client/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/rpungello/sdk-client.svg?style=flat-square)](https://packagist.org/packages/rpungello/sdk-client)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require rpungello/sdk-client
```

## Usage

### Instantiate the client

```php
$client = new Rpungello\SdkClient('https://example.com');
```

### GET requests

```php
// Returns a Psr\Http\Message\ResponseInterface response
$response = $client->get('/api/v1/users');

// Specify a query string
$response = $client->get('/api/v1/users', [
    'page' => 1,
    'limit' => 10,
]);

// Returns a json-decoded array from the response body
$response = $client->getJson('/api/v1/users');

// Takes the JSON data returned and wraps it in a DTO for static typing
$response = $client->getDto('/api/v1/users/1', UserDto::class);

// Takes the JSON data returned and converts it to an array of DTOs for static typing
$response = $client->getDtoArray('/api/v1/users', UserDto::class);
```

### PUT/POST/PATCH requests

```php
// Returns a Psr\Http\Message\ResponseInterface response
$response = $client->post('/api/v1/users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Returns a json-decoded array from the response body
$response = $client->postJson('/api/v1/users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Takes the JSON data returned and wraps it in a DTO for static typing
$user = new UserDto([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
$response = $client->postDto('/api/v1/users', $user);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Pungello](https://github.com/rpungello)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
