<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `GeolocationInfo` (_shared/src/common/Geolocation/IGeolocationDownloader.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class GeolocationInfo
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $ip = null,
        public ?string $hostname = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
        public ?string $loc = null,
        public ?string $org = null,
        public ?string $postal = null,
        public ?string $timezone = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ip: Read::string($data, 'ip'),
            hostname: Read::string($data, 'hostname'),
            city: Read::string($data, 'city'),
            region: Read::string($data, 'region'),
            country: Read::string($data, 'country'),
            loc: Read::string($data, 'loc'),
            org: Read::string($data, 'org'),
            postal: Read::string($data, 'postal'),
            timezone: Read::string($data, 'timezone'),
            raw: $data,
        );
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }
}
