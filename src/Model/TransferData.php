<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `TransferData` (api/src/api/Models/TransferData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class TransferData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $transferId = null,
        public ?string $status = null,
        public ?string $reference = null,
        public ?string $groupReference = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?float $totalAmount = null,
        public ?string $providerId = null,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?string $account = null,
        public ?string $chargeId = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            transferId: Read::string($data, 'transferId'),
            status: Read::string($data, 'status'),
            reference: Read::string($data, 'reference'),
            groupReference: Read::string($data, 'groupReference'),
            dateAdded: Read::date($data, 'dateAdded'),
            totalAmount: Read::float($data, 'totalAmount'),
            providerId: Read::string($data, 'providerId'),
            amount: Read::float($data, 'amount'),
            currency: Read::string($data, 'currency'),
            account: Read::string($data, 'account'),
            chargeId: Read::string($data, 'chargeId'),
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
