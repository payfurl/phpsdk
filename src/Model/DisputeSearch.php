<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /dispute (DisputeController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class DisputeSearch
{
    public function __construct(
        public ?string $transactionId = null,
        public ?string $providerId = null,
        public ?string $status = null,
        public ?string $stage = null,
        public ?string $reasonCategory = null,
        public ?string $currency = null,
        public ?float $amountGreaterThan = null,
        public ?float $amountLessThan = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?\DateTimeInterface $dueBefore = null,
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
            'transactionId' => $this->transactionId,
            'providerId' => $this->providerId,
            'status' => $this->status,
            'stage' => $this->stage,
            'reasonCategory' => $this->reasonCategory,
            'currency' => $this->currency,
            'amountGreaterThan' => $this->amountGreaterThan,
            'amountLessThan' => $this->amountLessThan,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'dueBefore' => $this->dueBefore,
            'sortBy' => $this->sortBy,
            'sortOrder' => $this->sortOrder,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
