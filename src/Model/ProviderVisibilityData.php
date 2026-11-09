<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ProviderVisibilityDataModel` (api/src/api/Models/ProviderData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ProviderVisibilityData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $currency = null,
        public ?float $lessThanAmount = null,
        public ?float $greaterThanAmount = null,
        public ?bool $applyCurrencyConversion = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            currency: Read::string($data, 'currency'),
            lessThanAmount: Read::float($data, 'lessThanAmount'),
            greaterThanAmount: Read::float($data, 'greaterThanAmount'),
            applyCurrencyConversion: Read::bool($data, 'applyCurrencyConversion'),
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
