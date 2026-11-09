<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /payment_method (PaymentMethodController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class PaymentMethodSearch
{
    public function __construct(
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $providerId = null,
        public ?string $customerId = null,
        public ?PaymentType $paymentType = null,
        public ?string $cardType = null,
        public ?string $search = null,
        public ?string $sortBy = null,
        public ?string $sortOrder = null,
        public ?bool $includeRemoved = null,
        public ?int $limit = null,
        public ?int $skip = null,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return Write::query([
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'providerId' => $this->providerId,
            'customerId' => $this->customerId,
            'paymentType' => $this->paymentType,
            'cardType' => $this->cardType,
            'search' => $this->search,
            'sortBy' => $this->sortBy,
            'sortOrder' => $this->sortOrder,
            'includeRemoved' => $this->includeRemoved,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
