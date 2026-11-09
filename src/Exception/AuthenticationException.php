<?php

declare(strict_types=1);

namespace payFURL\Sdk\Exception;

/**
 * The key is wrong, revoked, or not allowed to call the endpoint (HTTP 401 or 403). A public key works only for `info->providers()`.
 */
final class AuthenticationException extends PayfurlException {}
