<?php

namespace Rpungello\SdkClient\Drivers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\ConnectionException as LaravelConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException as LaravelRequestException;
use Illuminate\Support\Uri;
use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;
use Rpungello\SdkClient\Exceptions\ConnectionException;
use Rpungello\SdkClient\Exceptions\RequestException;

class LaravelDriver extends Driver
{
    private Factory $http;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(Application $app, string $baseUri)
    {
        parent::__construct($baseUri);

        $this->http = $app->make(Factory::class);
    }

    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        try {
            return $this->pendingRequest($headers)
                ->get($uri, $query)
                ->toPsrResponse();
        } catch (LaravelConnectionException $e) {
            throw ConnectionException::fromPrevious($e);
        } catch (LaravelRequestException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        try {
            return $this->pendingRequest($headers)
                ->post($uri, $body)
                ->toPsrResponse();
        } catch (LaravelConnectionException $e) {
            throw ConnectionException::fromPrevious($e);
        } catch (LaravelRequestException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        try {
            return $this->pendingRequest($headers)
                ->asMultipart()
                ->post($uri, $body)
                ->toPsrResponse();
        } catch (LaravelConnectionException $e) {
            throw ConnectionException::fromPrevious($e);
        } catch (LaravelRequestException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        try {
            return $this->pendingRequest($headers)
                ->put($uri, $body)
                ->toPsrResponse();
        } catch (LaravelConnectionException $e) {
            throw ConnectionException::fromPrevious($e);
        } catch (LaravelRequestException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        try {
            return $this->pendingRequest($headers)
                ->patch($uri, $body)
                ->toPsrResponse();
        } catch (LaravelConnectionException $e) {
            throw ConnectionException::fromPrevious($e);
        } catch (LaravelRequestException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    protected function pendingRequest(array $headers = []): PendingRequest
    {
        return $this->http->withHeaders($headers)
            ->baseUrl($this->baseUri)
            ->acceptJson()
            ->throw();
    }

    public function getRelativeUri(string $path, array $query = []): string
    {
        return (new Uri($this->baseUri))->withPath($path)->withQuery($query);
    }
}
