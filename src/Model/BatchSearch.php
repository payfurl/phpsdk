<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /batch (BatchController.SearchBatch). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class BatchSearch
{
    public function __construct(
        public ?string $description = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $status = null,
        public ?int $limit = null,
        public ?int $skip = null,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return Write::query([
            'description' => $this->description,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'status' => $this->status,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
