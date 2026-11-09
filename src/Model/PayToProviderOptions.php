<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PayToProviderOptions` (_shared/src/api.core/DTO/AvailablePaymentTypes.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PayToProviderOptions
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $providerId = null,
        public ?float $maximumAmount = null,
        public ?string $description = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            providerId: Read::string($data, 'providerId'),
            maximumAmount: Read::float($data, 'maximumAmount'),
            description: Read::string($data, 'description'),
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
