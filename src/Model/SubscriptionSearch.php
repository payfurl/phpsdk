<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /subscription (SubscriptionController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class SubscriptionSearch
{
    public function __construct(
        public ?float $amountGreaterThan = null,
        public ?float $amountLessThan = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $currency = null,
        public ?SubscriptionStatus $status = null,
        public ?string $paymentMethodId = null,
        public ?string $sortBy = null,
        public ?int $limit = null,
        public ?int $skip = null,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return Write::query([
            'amountGreaterThan' => $this->amountGreaterThan,
            'amountLessThan' => $this->amountLessThan,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'currency' => $this->currency,
            'status' => $this->status,
            'paymentMethodId' => $this->paymentMethodId,
            'sortBy' => $this->sortBy,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
