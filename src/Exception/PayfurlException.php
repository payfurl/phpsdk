<?php

declare(strict_types=1);

namespace payFURL\Sdk\Exception;

/**
 * Every error the SDK throws. Catch a subclass for one kind:
 *
 * - `AuthenticationException`: the key is wrong, or not allowed to call the endpoint (401, 403);
 * - `InvalidRequestException`: the API rejected the request's fields (400 with `ErrorCode::ValidationError`), or the
 *   SDK did before sending it (`$httpCode` 0);
 * - `NotFoundException`: no such resource (404);
 * - `ApiException`: any other API error, such as a declined charge (400) or a server error (5xx);
 * - `NetworkException`: no response arrived (a network error or a timeout).
 *
 * The properties are the API's error response (`Error`, services/api/src/api/Models/Error.cs).
 */
abstract class PayfurlException extends \RuntimeException
{
    /**
     * @param int $httpCode the response's HTTP status; 0 when there was none
     * @param int|null $errorCode the API's error code: `ErrorCode::tryFrom()` names it
     * @param string|null $type the error code's documentation page
     * @param string|null $resource the API path that failed
     * @param array<string, string>|null $details field-level messages, such as which field is invalid
     * @param string|null $chargeId the declined charge, when the error is a charge's
     * @param string|null $traceId what support asks for (also the response's x-request-id header)
     */
    public function __construct(
        string $message,
        public readonly int $httpCode = 0,
        public readonly ?int $errorCode = null,
        public readonly bool $isRetryable = false,
        public readonly ?string $type = null,
        public readonly ?string $resource = null,
        public readonly ?array $details = null,
        public readonly ?string $gatewayCode = null,
        public readonly ?string $gatewayMessage = null,
        public readonly ?string $chargeId = null,
        public readonly ?string $traceId = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $errorCode ?? 0, $previous);
    }
}
