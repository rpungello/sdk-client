<?php

namespace Rpungello\SdkClient;

use Psr\Http\Message\ResponseInterface;
use Rpungello\SdkClient\Drivers\Driver;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SdkClient
{
    public function __construct(protected Driver $driver)
    {
    }

    /**
     * Performs a GET request, returning the raw response
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return ResponseInterface
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->driver->get($uri, $query, $headers);
    }

    /**
     * @param string $uri
     * @param array|null $body
     * @param string $dtoClass
     * @param array $headers
     * @return DataTransferObject
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
     */
    public function postJson(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ?array
    {
        return json_decode(
            $this->post($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a POST request with a multipart body, returning the raw response
     *
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function postMultipart(string $uri, array $body, array $headers = []): ResponseInterface
    {
        return $this->driver->postMultipart($uri, $body, $headers);
    }

    /**
     * Performs a POST request with a multipart body, returning the response as a DTO
     *
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return array|null
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
     */
    public function postMultipartAsDto(string $uri, array $body, string $dtoClass, array $headers = []): DataTransferObject
    {
        return new $dtoClass($this->postMultipartAsJson($uri, $body, $headers));
    }

    /**
     * Performs a POST request, returning the raw response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function post(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->driver->post($uri, $body, $headers);
    }

    /**
     * Performs a standard GET request, parsing the result as a JSON array
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @return array|null
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
     */
    public function putJson(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ?array
    {
        return json_decode(
            $this->put($uri, $body, $headers)->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }

    /**
     * Performs a PUT request, returning the raw response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function put(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->driver->put($uri, $body, $headers);
    }

    /**
     * Performs a PATCH request, returning the raw response
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function patch(string $uri, array|DataTransferObject|null $body = null, array $headers = []): ResponseInterface
    {
        return $this->driver->patch($uri, $body, $headers);
    }

    /**
     * Performs a PATCH request, parsing the response as a JSON array
     *
     * @param string $uri
     * @param array|DataTransferObject|null $body
     * @param array $headers
     * @return array|null
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


    public static function convertJsonToMultipart(array $json): array
    {
        $multipart = [];

        foreach ($json as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => static::formatMultipartValue($value),
            ];
        }

        return $multipart;
    }

    private static function formatMultipartValue(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return serialize($value);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
