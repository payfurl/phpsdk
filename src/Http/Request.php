<?php

declare(strict_types=1);

namespace payFURL\Sdk\Http;

/**
 * One HTTP request to the API, as a transport sends it.
 */
final readonly class Request
{
    /**
     * @param non-empty-string $method
     * @param non-empty-string $url
     * @param array<string, string> $headers
     */
    public function __construct(
        public string $method,
        public string $url,
        public array $headers,
        public ?string $body,
        public int $timeoutMilliseconds,
        public bool $verifyTls = true,
    ) {}
}
