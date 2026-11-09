<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `Geolocation` (api/src/api/Models/Geolocation.cs), in a request.
 */
final readonly class Geolocation
{
    public function __construct(
        public ?float $longitude = null,
        public ?float $latitude = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ]);
    }
}
