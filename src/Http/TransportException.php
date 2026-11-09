<?php

declare(strict_types=1);

namespace payFURL\Sdk\Http;

/**
 * A request got no response: the network failed or the request timed out. The client turns it into a
 * `NetworkException`.
 */
final class TransportException extends \RuntimeException
{
    public function __construct(string $message, public readonly bool $timedOut = false, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
