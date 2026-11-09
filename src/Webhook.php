<?php

declare(strict_types=1);

namespace payFURL\Sdk;

use payFURL\Sdk\Exception\InvalidRequestException;

/**
 * Checks that a webhook came from payFURL. payFURL signs each webhook's body with the account's webhook signing key
 * (the portal's Webhook Secret Key) and sends the signature in the `x-payfurl-signature` header: base64 of the body's
 * HMAC-SHA256.
 *
 *     $event = Webhook::parse(file_get_contents('php://input'), $_SERVER['HTTP_X_PAYFURL_SIGNATURE'] ?? null, $key);
 *     if ($event['meta']['type'] === 'transaction') { $chargeId = $event['data']['chargeId']; }
 */
final class Webhook
{
    /**
     * Whether the signature is the body's, compared in constant time.
     */
    public static function verify(string $body, ?string $signature, string $signingKey): bool
    {
        if ($signature === null || $signature === '' || $signingKey === '') {
            return false;
        }
        $expected = base64_encode(hash_hmac('sha256', $body, $signingKey, true));

        return hash_equals($expected, $signature);
    }

    /**
     * The webhook's JSON (`data` and `meta`) once its signature checks out.
     *
     * @return array<array-key, mixed>
     *
     * @throws InvalidRequestException when the signature does not match the body, or the body is not JSON
     */
    public static function parse(string $body, ?string $signature, string $signingKey): array
    {
        if (!self::verify($body, $signature, $signingKey)) {
            throw new InvalidRequestException('The webhook\'s signature does not match its body: it is not from payFURL');
        }
        $event = json_decode($body, true, 512, JSON_BIGINT_AS_STRING);
        if (!is_array($event)) {
            throw new InvalidRequestException('The webhook\'s body is not JSON');
        }

        return $event;
    }
}
