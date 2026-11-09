<?php

declare(strict_types=1);

namespace payFURL\Sdk\Internal;

use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\ErrorCode;
use payFURL\Sdk\Exception\ApiException;
use payFURL\Sdk\Exception\AuthenticationException;
use payFURL\Sdk\Exception\InvalidRequestException;
use payFURL\Sdk\Exception\NetworkException;
use payFURL\Sdk\Exception\NotFoundException;
use payFURL\Sdk\Exception\PayfurlException;
use payFURL\Sdk\Http\CurlTransport;
use payFURL\Sdk\Http\Request;
use payFURL\Sdk\Http\Transport;
use payFURL\Sdk\Http\TransportException;
use payFURL\Sdk\PayfurlClient;

/**
 * Sends one API call: the URL, the key and User-Agent headers, JSON in and out, retries for GETs, and the API's
 * errors as exceptions.
 *
 * @internal
 */
final class ApiClient
{
    private readonly Transport $transport;

    /**
     * @param non-empty-string $baseUrl
     */
    public function __construct(
        private readonly string $key,
        private readonly string $baseUrl,
        private readonly ClientOptions $options,
        private readonly bool $verifyTls,
    ) {
        $this->transport = $options->transport ?? new CurlTransport();
    }

    /**
     * @param array<string, string> $query
     * @param array<string, string> $headers
     *
     * @return array<array-key, mixed>
     */
    public function get(string $path, array $query = [], array $headers = []): array
    {
        return $this->send('GET', $path, $query, null, $headers);
    }

    /**
     * @param array<string, mixed> $body
     *
     * @return array<array-key, mixed>
     */
    public function post(string $path, array $body = []): array
    {
        return $this->send('POST', $path, [], $body);
    }

    /**
     * @param array<string, mixed> $body
     *
     * @return array<array-key, mixed>
     */
    public function put(string $path, array $body): array
    {
        return $this->send('PUT', $path, [], $body);
    }

    /**
     * @param array<string, string> $query
     *
     * @return array<array-key, mixed>
     */
    public function delete(string $path, array $query = []): array
    {
        return $this->send('DELETE', $path, $query, null);
    }

    /**
     * A resource id as a path segment.
     *
     * @throws InvalidRequestException for an empty id, which would call another endpoint (GET /charge/ is a search)
     */
    public static function id(string $id, string $name): string
    {
        if (trim($id) === '') {
            throw new InvalidRequestException($name . ' is empty');
        }

        return rawurlencode($id);
    }

    /**
     * @param array<string, string> $query
     * @param non-empty-string $method
     * @param array<string, mixed>|null $body
     * @param array<string, string> $extraHeaders
     *
     * @return array<array-key, mixed>
     */
    private function send(string $method, string $path, array $query, ?array $body, array $extraHeaders = []): array
    {
        $headers = $extraHeaders + [
            'Accept' => 'application/json',
            'User-Agent' => 'payfurl-php/' . PayfurlClient::VERSION . ' PHP/' . PHP_VERSION,
            // A public key authenticates only the endpoints that take one, such as /info/providers.
            (str_starts_with($this->key, 'pub') ? 'x-publickey' : 'x-secretkey') => $this->key,
        ];
        $json = null;
        if ($body !== null) {
            $json = json_encode((object) $body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
            $headers['Content-Type'] = 'application/json';
        }
        $url = $this->baseUrl . $path . ($query === [] ? '' : '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986));
        $request = new Request($method, $url, $headers, $json, $this->options->timeoutMilliseconds, $this->verifyTls);

        // Only reads are retried: the API has no idempotency keys, so a second charge POST could charge twice.
        $retries = $method === 'GET' ? max(0, $this->options->maxRetries) : 0;
        for ($attempt = 0; ; $attempt++) {
            try {
                $response = $this->transport->send($request);
            } catch (TransportException $e) {
                if ($attempt < $retries) {
                    $this->wait($attempt);
                    continue;
                }
                throw new NetworkException($e->getMessage(), $e->timedOut, $e);
            }

            if ($response->status >= 200 && $response->status < 300) {
                return self::decode($response->body, $response->status);
            }
            $error = self::error($response->status, $response->body);
            if ($error->isRetryable && $attempt < $retries) {
                $this->wait($attempt);
                continue;
            }
            throw $error;
        }
    }

    private function wait(int $attempt): void
    {
        $milliseconds = $this->options->retryDelayMilliseconds * (2 ** $attempt);
        if ($milliseconds > 0) {
            usleep($milliseconds * 1000);
        }
    }

    /**
     * @return array<array-key, mixed>
     */
    private static function decode(string $body, int $status): array
    {
        if (trim($body) === '') {
            return [];
        }
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new ApiException('The API answered HTTP ' . $status . ' with a body that is not a JSON object or array', $status);
        }

        return $decoded;
    }

    /**
     * The API's error response (services/api/src/api/Models/Error.cs) as an exception.
     */
    private static function error(int $status, string $body): PayfurlException
    {
        $decoded = json_decode($body, true);
        $error = is_array($decoded) ? $decoded : [];
        $code = Read::int($error, 'code');
        // Some endpoints answer a bad request with a plain message (BadRequest("...")) instead of an Error.
        $text = is_string($decoded) ? $decoded : (is_array($decoded) ? '' : trim($body));
        $message = Read::string($error, 'message')
            ?? ($text !== '' ? substr($text, 0, 500) : 'HTTP ' . $status . ' from the payFURL API');
        $arguments = [
            $message,
            $status,
            $code,
            Read::bool($error, 'isRetryable') ?? ($status >= 500 || $status === 429),
            Read::string($error, 'type'),
            Read::string($error, 'resource'),
            Read::stringMap($error, 'details'),
            Read::string($error, 'gatewayCode'),
            Read::string($error, 'gatewayMessage'),
            Read::string($error, 'chargeId'),
            Read::string($error, 'traceId'),
        ];

        return match (true) {
            $status === 401, $status === 403 => new AuthenticationException(...$arguments),
            $status === 404 => new NotFoundException(...$arguments),
            ($status === 400 || $status === 422) && $code === ErrorCode::ValidationError->value => new InvalidRequestException(...$arguments),
            default => new ApiException(...$arguments),
        };
    }
}
