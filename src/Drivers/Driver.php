<?php

namespace Rpungello\SdkClient\Drivers;

use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;
use Rpungello\SdkClient\Exceptions\RequestException;

abstract class Driver
{
    public function __construct(protected readonly string $baseUri)
    {
    }

    /**
     * @throws RequestException
     */
    abstract public function get(string $uri, array $query = [], array $headers = []): ResponseInterface;

    /**
     * @throws RequestException
     */
    abstract public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;

    /**
     * @throws RequestException
     */
    abstract public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface;

    /**
     * @throws RequestException
     */
    abstract public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;

    /**
     * @throws RequestException
     */
    abstract public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;

    abstract public function getRelativeUri(string $path, array $query = []): string;
}
