<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /customer (CustomerController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class CustomerSearch
{
    public function __construct(
        public ?string $reference = null,
        public ?string $email = null,
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $search = null,
        public ?string $sortBy = null,
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
            'reference' => $this->reference,
            'email' => $this->email,
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'search' => $this->search,
            'sortBy' => $this->sortBy,
            'includeRemoved' => $this->includeRemoved,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
