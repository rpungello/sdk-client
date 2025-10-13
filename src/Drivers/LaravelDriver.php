<?php

namespace Rpungello\SdkClient\Drivers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;

class LaravelDriver extends Driver
{
    private Factory $http;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(Application $app, string $baseUri)
    {
        $this->http = $app->make(Factory::class)->baseUrl($baseUri);
    }

    /**
     * @throws ConnectionException
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->get($uri, $query)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->post($uri, $body)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->asMultipart()
            ->post($uri, $body)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->put($uri, $body)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->patch($uri, $body)
            ->toPsrResponse();
    }
}
