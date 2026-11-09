<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PayToSummary` (_shared/src/common/Models/PayToSummary.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PayToSummary
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $payToId = null,
        public ?string $status = null,
        public ?string $payerName = null,
        public ?PayerPayIdDetails $payerPayIdDetails = null,
        public ?DirectDebitDetails $payerDirectDebitDetails = null,
        public ?string $providerId = null,
        public ?string $providerType = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            payToId: Read::string($data, 'payToId'),
            status: Read::string($data, 'status'),
            payerName: Read::string($data, 'payerName'),
            payerPayIdDetails: Read::object($data, 'payerPayIdDetails', PayerPayIdDetails::class),
            payerDirectDebitDetails: Read::object($data, 'payerDirectDebitDetails', DirectDebitDetails::class),
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
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
