<?php

namespace Rpungello\SdkClient\Drivers;

use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;

abstract class Driver
{
    abstract public function get(string $uri, array $query = [], array $headers = []): ResponseInterface;

    abstract public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;

    abstract public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface;

    abstract public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;

    abstract public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface;
}
