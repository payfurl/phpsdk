<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `DisputeResponse` (_shared/src/common/Models/DisputeResponse.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class Dispute
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $disputeId = null,
        public ?string $transactionId = null,
        public ?string $providerId = null,
        public ?string $type = null,
        public ?string $gatewayDisputeId = null,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?float $fee = null,
        public ?string $status = null,
        public ?string $stage = null,
        public ?string $reasonCategory = null,
        public ?string $reason = null,
        public ?string $networkReasonCode = null,
        public ?string $cardBrand = null,
        public ?\DateTimeImmutable $disputeDate = null,
        public ?\DateTimeImmutable $evidenceDueBy = null,
        public ?int $submissionCount = null,
        public ?bool $isChargeRefundable = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?\DateTimeImmutable $dateUpdated = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            disputeId: Read::string($data, 'disputeId'),
            transactionId: Read::string($data, 'transactionId'),
            providerId: Read::string($data, 'providerId'),
            type: Read::string($data, 'type'),
            gatewayDisputeId: Read::string($data, 'gatewayDisputeId'),
            amount: Read::float($data, 'amount'),
            currency: Read::string($data, 'currency'),
            fee: Read::float($data, 'fee'),
            status: Read::string($data, 'status'),
            stage: Read::string($data, 'stage'),
            reasonCategory: Read::string($data, 'reasonCategory'),
            reason: Read::string($data, 'reason'),
            networkReasonCode: Read::string($data, 'networkReasonCode'),
            cardBrand: Read::string($data, 'cardBrand'),
            disputeDate: Read::date($data, 'disputeDate'),
            evidenceDueBy: Read::date($data, 'evidenceDueBy'),
            submissionCount: Read::int($data, 'submissionCount'),
            isChargeRefundable: Read::bool($data, 'isChargeRefundable'),
            dateAdded: Read::date($data, 'dateAdded'),
            dateUpdated: Read::date($data, 'dateUpdated'),
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
