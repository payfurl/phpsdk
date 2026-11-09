<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `IinData` (_shared/src/api.core/DTO/IinData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class IinData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $scheme = null,
        public ?string $country = null,
        public ?string $issuer = null,
        public ?string $countryCode = null,
        public ?string $cardType = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            scheme: Read::string($data, 'scheme'),
            country: Read::string($data, 'country'),
            issuer: Read::string($data, 'issuer'),
            countryCode: Read::string($data, 'countryCode'),
            cardType: Read::string($data, 'cardType'),
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
