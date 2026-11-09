<?php

declare(strict_types=1);

namespace payFURL\Sdk;

/**
 * Which payFURL environment a client calls. The key's region (its last part, such as `-au`) picks the regional
 * host; `ClientOptions::$baseUrl` overrides both.
 */
enum Environment: string
{
    /** The API on this machine (https://localhost:5001), with TLS verification off for its development certificate. */
    case Local = 'local';
    case Development = 'development';
    case Sandbox = 'sandbox';
    case Production = 'production';

    /**
     * The API's base URL for a key's region (`au`), or the global host when the region has none of its own.
     */
    public function baseUrl(?string $region): string
    {
        return match ([$this, $region]) {
            [self::Development, 'au'] => 'https://develop-api-au.payfurl.com',
            [self::Sandbox, 'au'] => 'https://sandbox-api-au.payfurl.com',
            [self::Production, 'au'] => 'https://api-au.payfurl.com',
            default => match ($this) {
                self::Local => 'https://localhost:5001',
                self::Development => 'https://develop-api.payfurl.com',
                self::Sandbox => 'https://sandbox-api.payfurl.com',
                self::Production => 'https://api.payfurl.com',
            },
        };
    }
}
