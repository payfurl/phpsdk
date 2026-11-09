<?php

declare(strict_types=1);

namespace payFURL\Sdk\Exception;

/**
 * An API error that is not one of the other kinds: a declined or failed charge (HTTP 400 with the gateway's error code), a conflict, or a server error (5xx). `$isRetryable` says whether the same request may succeed later.
 */
final class ApiException extends PayfurlException {}
