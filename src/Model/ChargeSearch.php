<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /charge (ChargeController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class ChargeSearch
{
    public function __construct(
        public ?string $reference = null,
        public ?string $providerId = null,
        public ?float $amountGreaterThan = null,
        public ?float $amountLessThan = null,
        public ?string $customerId = null,
        public ?TransactionStatus $status = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $paymentMethodId = null,
        public ?PaymentType $paymentType = null,
        public ?string $cardType = null,
        public ?string $currency = null,
        public ?string $cardNumber = null,
        public ?string $cardholder = null,
        public ?string $batchId = null,
        public ?string $subscriptionId = null,
        public ?string $sortBy = null,
        public ?string $sortOrder = null,
        public ?int $limit = null,
        public ?int $skip = null,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return Write::query([
            'reference' => $this->reference,
            'providerId' => $this->providerId,
            'amountGreaterThan' => $this->amountGreaterThan,
            'amountLessThan' => $this->amountLessThan,
            'customerId' => $this->customerId,
            'status' => $this->status,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'paymentMethodId' => $this->paymentMethodId,
            'paymentType' => $this->paymentType,
            'cardType' => $this->cardType,
            'currency' => $this->currency,
            'cardNumber' => $this->cardNumber,
            'cardholder' => $this->cardholder,
            'batchId' => $this->batchId,
            'subscriptionId' => $this->subscriptionId,
            'sortBy' => $this->sortBy,
            'sortOrder' => $this->sortOrder,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
