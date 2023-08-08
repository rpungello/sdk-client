<?php

namespace Rpungello\SdkClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SdkClient
{
    protected ?GuzzleClient $guzzle;

    public function __construct(protected string $baseUri, protected ?HandlerStack $handler = null)
    {
        $this->guzzle = static::getGuzzleClient($this->baseUri, $this->handler);
    }

    /**
     * Instantiates a new Guzzle client, which will be used when interfacing with the Web Distribution API.
     *
     * @param string $baseUri
     * @param HandlerStack|null $handler
     * @return GuzzleClient
     */
    protected static function getGuzzleClient(string $baseUri, ?HandlerStack $handler = null): GuzzleClient
    {
        return new GuzzleClient(
            static::getGuzzleClientConfig($baseUri, $handler),
        );
    }

    /**
     * Gets the config array for a new Guzzle client
     *
     * @param string $baseUri
     * @param HandlerStack|null $handler
     * @return array
     */
    protected static function getGuzzleClientConfig(string $baseUri, ?HandlerStack $handler = null): array
    {
        $config = [
            'base_uri' => $baseUri,
            'cookies' => true,
            'headers' => [
                'accept' => 'application/json',
            ],
        ];

        if (! is_null($handler)) {
            $config['handler'] = $handler;
        }

        return $config;
    }

    /**
     * Performs a GET request against the Web Distribution API
     *
     * @param string $uri
     * @param array $query
     * @return Response
     * @throws GuzzleException
     */
    public function get(string $uri, array $query = []): Response
    {
        return $this->guzzle->get(
            $uri,
            [
                RequestOptions::QUERY => $query,
            ]
        );
    }

    /**
     * @param string $uri
     * @param DataTransferObject|null $dto
     * @return DataTransferObject
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function postDto(string $uri, DataTransferObject $dto = null): mixed
    {
        $class = get_class($dto);

        return new $class($this->postJson($uri, $dto));
    }

    /**
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @return array
     * @throws GuzzleException
     */
    public function postJson(string $uri, array|DataTransferObject|null $body = null): array
    {
        return json_decode(
            $this->post($uri, $body)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @return Response
     * @throws GuzzleException
     */
    public function post(string $uri, array|DataTransferObject|null $body = null): Response
    {
        if ($body instanceof DataTransferObject) {
            $bodyJson = $body->toArray();
        } else {
            $bodyJson = $body;
        }

        $requestOptions = [];

        if (! empty($bodyJson)) {
            $requestOptions[RequestOptions::JSON] = $bodyJson;
        }

        return $this->guzzle->post($uri, $requestOptions);
    }

    /**
     * Performs a standard GET request, but parses the result as a JSON array
     *
     * @param string $uri
     * @param array $query
     * @return array
     * @throws GuzzleException
     */
    public function getJson(string $uri, array $query = []): array
    {
        return json_decode(
            $this->get($uri, $query)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a standard GET request, but parses the result as a JSON array
     *
     * @param string $uri
     * @param string $dtoClass
     * @param array $query
     * @return DataTransferObject
     * @throws GuzzleException
     */
    public function getDto(string $uri, string $dtoClass, array $query = []): DataTransferObject
    {
        return new $dtoClass(json_decode(
            $this->get($uri, $query)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        ));
    }

    /**
     * @param string $uri
     * @param DataTransferObject|null $dto
     * @param array $data
     * @return DataTransferObject
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function putDto(string $uri, DataTransferObject $dto = null, array $data = []): mixed
    {
        $class = get_class($dto);

        return new $class(
            $this->putJson(
                $uri,
                array_merge($data, $dto->toArray())
            )
        );
    }

    /**
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @return array
     * @throws GuzzleException
     */
    public function putJson(string $uri, array|DataTransferObject|null $body = null): array
    {
        return json_decode(
            $this->put($uri, $body)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @return Response
     * @throws GuzzleException
     */
    public function put(string $uri, array|DataTransferObject|null $body = null): Response
    {
        if ($body instanceof DataTransferObject) {
            $bodyJson = $body->toArray();
        } else {
            $bodyJson = $body;
        }

        $requestOptions = [];

        if (! empty($bodyJson)) {
            $requestOptions[RequestOptions::JSON] = $bodyJson;
        }

        return $this->guzzle->put($uri, $requestOptions);
    }
}
