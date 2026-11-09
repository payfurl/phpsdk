<?php

declare(strict_types=1);

namespace payFURL\Sdk\Exception;

/**
 * No response arrived: the network failed, or the request timed out (`$timedOut`). Always retryable for a read. For
 * a charge, the API may still have processed it: search by its reference before charging again.
 */
final class NetworkException extends PayfurlException
{
    public function __construct(string $message, public readonly bool $timedOut = false, ?\Throwable $previous = null)
    {
        parent::__construct($message, isRetryable: true, previous: $previous);
    }
}
