<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ProviderCostDataModel` (api/src/api/Models/ProviderData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ProviderCostData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?bool $isInternationalCard = null,
        public ?string $cardScheme = null,
        public ?float $transactionCost = null,
        public ?float $transactionPercentage = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isInternationalCard: Read::bool($data, 'isInternationalCard'),
            cardScheme: Read::string($data, 'cardScheme'),
            transactionCost: Read::float($data, 'transactionCost'),
            transactionPercentage: Read::float($data, 'transactionPercentage'),
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
