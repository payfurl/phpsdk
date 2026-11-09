<?php

declare(strict_types=1);

namespace payFURL\Sdk\Exception;

/**
 * The API rejected the request's fields (HTTP 400, `ErrorCode::ValidationError`; `$details` names them), or the SDK did before sending it (`$httpCode` 0).
 */
final class InvalidRequestException extends PayfurlException {}
