<?php

declare(strict_types=1);

namespace payFURL\Sdk\Http;

/**
 * Sends requests to the API. `CurlTransport` is the default; `FakeTransport` answers in tests. Implement it to use
 * another HTTP client, or to log what the SDK sends.
 */
interface Transport
{
    /**
     * Returns the API's response, whatever its HTTP status.
     *
     * @throws TransportException when no response arrived: a network error or a timeout
     */
    public function send(Request $request): Response;
}
