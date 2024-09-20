<?php

namespace Rpungello\SdkClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SdkClient
{
    protected ?GuzzleClient $guzzle;

    public function __construct(protected string $baseUri, protected ?HandlerStack $handler = null, protected ?string $userAgent = null, protected ?string $accept = 'application/json', protected bool $cookies = false)
    {
        $this->guzzle = static::getGuzzleClient();
    }

    /**
     * Instantiates a new Guzzle client
     *
     * @return GuzzleClient
     */
    protected function getGuzzleClient(): GuzzleClient
    {
        return new GuzzleClient(
            $this->getGuzzleClientConfig(),
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

    /**
     * Performs a GET request, returning the raw Guzzle response
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        $requestOptions = [
            RequestOptions::QUERY => $query,
        ];

        if (! empty($headers)) {
            $requestOptions[RequestOptions::HEADERS] = $headers;
        }

        return $this->guzzle->get(
            $uri,
            $requestOptions
        );
    }

    /**
     * @param string $uri
     * @param array|null $body
     * @param string $dtoClass
     * @param array $headers
     * @return DataTransferObject
     * @throws GuzzleException
     */
    public function postJsonAsDto(string $uri, array|null $body, string $dtoClass, array $headers = []): mixed
    {
        return new $dtoClass($this->postJson($uri, $body, $headers));
    }

    /**
     * Performs a POST request, parsing the response as a DTO
     *
     * @param string $uri
     * @param DataTransferObject|null $dto
     * @param array $headers
     * @return DataTransferObject
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function postDto(string $uri, DataTransferObject $dto = null, array $headers = []): mixed
    {
        $class = get_class($dto);

        return new $class($this->postJson($uri, $dto, $headers));
    }

    /**
     * Performs a POST request, parsing the response as a JSON array
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return array|null
     * @throws GuzzleException
     */
    public function postJson(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ?array
    {
        return json_decode(
            $this->post($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a POST request with a multipart body, returning the raw Guzzle response
     *
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers, false);

        return $this->guzzle->post($uri, $requestOptions);
    }

    /**
     * Performs a POST request with a multipart body, returning the response as a DTO
     *
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return array|null
     * @throws GuzzleException
     */
    public function postMultipartAsJson(string $uri, array $body, array $headers = []): ?array
    {
        return json_decode(
            $this->postMultipart($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a POST request with a multipart body, returning the response as a DTO
     *
     * @param string $uri
     * @param array $body
     * @param string $dtoClass
     * @param array $headers
     * @return DataTransferObject
     * @throws GuzzleException
     */
    public function postMultipartAsDto(string $uri, array $body, string $dtoClass, array $headers = []): DataTransferObject
    {
        return new $dtoClass($this->postMultipartAsJson($uri, $body, $headers));
    }

    /**
     * Performs a POST request, returning the raw Guzzle response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        return $this->guzzle->post($uri, $requestOptions);
    }

    /**
     * Performs a standard GET request, parsing the result as a JSON array
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return array|null
     * @throws GuzzleException
     */
    public function getJson(string $uri, array $query = [], array $headers = []): ?array
    {
        return json_decode(
            $this->get($uri, $query, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a standard GET request, but parses the result as a DTO
     *
     * @param string $uri
     * @param string $dtoClass
     * @param array $query
     * @param array $headers
     * @return DataTransferObject
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function getDto(string $uri, string $dtoClass, array $query = [], array $headers = []): DataTransferObject
    {
        return new $dtoClass(
            $this->getJson($uri, $query, $headers)
        );
    }

    /**
     * Performs a standard GET request, but parses the result as an array of DTOs
     *
     * @param string $uri
     * @param string $dtoClass
     * @param array $query
     * @param array $headers
     * @return DataTransferObject[]
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function getDtoArray(string $uri, string $dtoClass, array $query = [], array $headers = []): array
    {
        $array = $this->getJson($uri, $query, $headers);

        return array_map(
            fn (array $item) => new $dtoClass($item),
            $array
        );
    }

    /**
     * Performs a PUT request, parsing the response as a DTO
     *
     * @param string $uri
     * @param DataTransferObject|null $dto
     * @param array $data
     * @param array $headers
     * @return DataTransferObject
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function putDto(string $uri, DataTransferObject $dto = null, array $data = [], array $headers = []): mixed
    {
        $class = get_class($dto);

        return new $class(
            $this->putJson(
                $uri,
                array_merge($data, $dto->toArray()),
                $headers
            )
        );
    }

    /**
     * Performs a PUT request, parsing the response as a JSON array
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return array|null
     * @throws GuzzleException
     */
    public function putJson(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ?array
    {
        return json_decode(
            $this->put($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a PUT request, returning the raw Guzzle response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        return $this->guzzle->put($uri, $requestOptions);
    }

    /**
     * Performs a PATCH request, returning the raw Guzzle response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        $requestOptions = $this->getRequestOptions($body, $headers);

        return $this->guzzle->patch($uri, $requestOptions);
    }

    /**
     * Performs a PATCH request, parsing the response as a JSON array
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return array|null
     * @throws GuzzleException
     */
    public function patchJson(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ?array
    {
        return json_decode(
            $this->patch($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * @param string $uri
     * @param DataTransferObject|null $dto
     * @param array $data
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function patchDto(string $uri, DataTransferObject $dto = null, array $data = [], array $headers = []): mixed
    {
        $class = get_class($dto);

        return new $class(
            $this->patchJson(
                $uri,
                array_merge($data, $dto->toArray()),
                $headers
            )
        );
    }

    /**
     * @param DataTransferObject|array|null $body
     * @param array $headers
     * @param bool $json
     * @return array
     */
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

    public static function convertJsonToMultipart(array $json): array
    {
        $multipart = [];

        foreach ($json as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }

        return $multipart;
    }
}
