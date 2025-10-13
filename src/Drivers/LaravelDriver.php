<?php

namespace Rpungello\SdkClient\Drivers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;

class LaravelDriver extends Driver
{
    public function __construct(protected PendingRequest $http)
    {
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
