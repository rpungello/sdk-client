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
    public function __construct(Application $app, private readonly string $baseUri)
    {
        $this->http = $app->make(Factory::class);
    }

    /**
     * @throws ConnectionException
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->baseUrl($this->baseUri)
            ->get($uri, $query)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->baseUrl($this->baseUri)
            ->post($uri, $body)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->baseUrl($this->baseUri)
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
            ->baseUrl($this->baseUri)
            ->put($uri, $body)
            ->toPsrResponse();
    }

    /**
     * @throws ConnectionException
     */
    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->http->withHeaders($headers)
            ->baseUrl($this->baseUri)
            ->patch($uri, $body)
            ->toPsrResponse();
    }
}
