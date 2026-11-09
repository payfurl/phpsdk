<?php

declare(strict_types=1);

namespace payFURL\Sdk;

use payFURL\Sdk\Http\Transport;

/**
 * How a `PayfurlClient` sends requests. Every option has a default.
 */
final readonly class ClientOptions
{
    /**
     * @param string|null $baseUrl the API's URL, in place of the environment's and region's (such as
     *                             https://localhost:5001 for a local stack)
     * @param int $timeoutMilliseconds how long one request may take
     * @param int $maxRetries how many times a GET is retried after a network error, a timeout or a retryable API
     *                        error. Other requests are never retried: the API has no idempotency keys, so a
     *                        retried charge could charge twice.
     * @param int $retryDelayMilliseconds the wait before the first retry, doubled before each one after it
     * @param Transport|null $transport sends the requests; curl by default, `FakeTransport` in tests
     */
    public function __construct(
        public ?string $baseUrl = null,
        public int $timeoutMilliseconds = 60000,
        public int $maxRetries = 2,
        public int $retryDelayMilliseconds = 500,
        public ?Transport $transport = null,
    ) {}
}
