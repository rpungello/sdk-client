<?php

namespace Rpungello\SdkClient\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\DataTransferObject;
use Rpungello\SdkClient\Exceptions\RequestException;

class GuzzleDriver extends Driver
{
    protected ?GuzzleClient $guzzle;

    public function __construct(string $baseUri, protected ?HandlerStack $handler = null, protected ?string $userAgent = null, protected ?string $accept = 'application/json', protected bool $cookies = false)
    {
        parent::__construct($baseUri);

        $this->guzzle = $this->initializeGuzzleClient();
    }

    /**
     * Instantiates a new Guzzle client
     */
    protected function initializeGuzzleClient(): GuzzleClient
    {
        return new GuzzleClient(
            $this->getGuzzleClientConfig()
        );
    }

    /**
     * Gets the config array for a new Guzzle client
     *
     * @return array
     */
    protected function getGuzzleClientConfig(): array
    {
        $config = [
            'base_uri' => $this->baseUri,
            'cookies' => $this->cookies,
            'headers' => [],
        ];

        if (! is_null($this->handler)) {
            $config['handler'] = $this->handler;
        }

        if (! empty($this->userAgent)) {
            $config['headers']['user-agent'] = $this->userAgent;
        }

        if (! empty($this->accept)) {
            $config['headers']['accept'] = $this->accept;
        }

        return $config;
    }

    public function getRequestOptions(DataTransferObject|array|null $body, array $headers, bool $json = true): array
    {
        if ($body instanceof DataTransferObject) {
            $formattedBody = $body->toArray();
        } else {
            $formattedBody = $body;
        }

        $requestOptions = [];

        if (! empty($formattedBody)) {
            if ($json) {
                $requestOptions[RequestOptions::JSON] = $formattedBody;
            } else {
                $requestOptions[RequestOptions::MULTIPART] = $formattedBody;
            }
        }

        if (! empty($headers)) {
            $requestOptions[RequestOptions::HEADERS] = $headers;
        }

        return $requestOptions;
    }

    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        $requestOptions = [
            RequestOptions::QUERY => $query,
        ];

        if (! empty($headers)) {
            $requestOptions[RequestOptions::HEADERS] = $headers;
        }

        try {
            return $this->guzzle->get(
                $uri,
                $requestOptions
            );
        } catch (GuzzleException|ClientException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers, false);

        try {
            return $this->guzzle->post($uri, $requestOptions);
        } catch (GuzzleException|ClientException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        try {
            return $this->guzzle->post($uri, $requestOptions);
        } catch (GuzzleException|ClientException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        try {
            return $this->guzzle->put($uri, $requestOptions);
        } catch (GuzzleException|ClientException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        try {
            return $this->guzzle->patch($uri, $requestOptions);
        } catch (GuzzleException|ClientException $e) {
            throw RequestException::fromPrevious($e);
        }
    }

    public function getRelativeUri(string $path, array $query = []): string
    {
        return (new Uri($this->baseUri))->withPath($path)->withQuery(http_build_query($query));
    }
}
