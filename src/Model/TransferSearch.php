<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /transfer (TransferController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class TransferSearch
{
    public function __construct(
        public ?string $reference = null,
        public ?string $providerId = null,
        public ?string $status = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
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
            'reference' => $this->reference,
            'providerId' => $this->providerId,
            'status' => $this->status,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'sortBy' => $this->sortBy,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
