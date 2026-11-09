<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /token (TokenController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class TokenSearch
{
    public function __construct(
        public ?string $providerId = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $status = null,
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
            'providerId' => $this->providerId,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'status' => $this->status,
            'sortBy' => $this->sortBy,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
