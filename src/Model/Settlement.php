<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `Settlement` (api/src/api/Models/Settlement.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class Settlement
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $settlementId = null,
        public ?\DateTimeImmutable $settlementDate = null,
        public ?\DateTimeImmutable $createdDate = null,
        public ?string $providerId = null,
        public ?string $providerName = null,
        public ?string $chargeId = null,
        public ?float $amount = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            settlementId: Read::string($data, 'settlementId'),
            settlementDate: Read::date($data, 'settlementDate'),
            createdDate: Read::date($data, 'createdDate'),
            providerId: Read::string($data, 'providerId'),
            providerName: Read::string($data, 'providerName'),
            chargeId: Read::string($data, 'chargeId'),
            amount: Read::float($data, 'amount'),
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
