<?php

namespace Rpungello\SdkClient\Exceptions;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Illuminate\Http\Client\RequestException as LaravelRequestException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class RequestException extends RuntimeException
{
    protected ?int $httpStatusCode = null;

    protected ?ResponseInterface $response = null;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (! is_null($previous)) {
            $this->httpStatusCode = static::extractHttpStatusCode($previous);
            $this->response = static::extractResponse($previous);
        }
    }

    public static function fromPrevious(Throwable $previous): self
    {
        $statusCode = static::extractHttpStatusCode($previous);

        if (empty($statusCode)) {
            return new ConnectionException($previous->getMessage(), $previous->getCode(), $previous);
        } elseif ($statusCode < Response::HTTP_INTERNAL_SERVER_ERROR) {
            return new ClientErrorException($previous->getMessage(), $previous->getCode(), $previous);
        } else {
            return new ServerErrorException($previous->getMessage(), $previous->getCode(), $previous);
        }
    }

    private static function extractHttpStatusCode(Throwable $previous): ?int
    {
        if ($previous instanceof GuzzleRequestException) {
            return $previous->getResponse()->getStatusCode();
        } elseif ($previous instanceof LaravelRequestException) {
            return $previous->response->getStatusCode();
        }

        return null;
    }

    private static function extractResponse(Throwable $previous): ?ResponseInterface
    {
        if ($previous instanceof GuzzleRequestException) {
            return $previous->getResponse();
        } elseif ($previous instanceof LaravelRequestException) {
            return $previous->response->toPsrResponse();
        }

        return null;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
