<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /payment_link (PaymentLinkController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class PaymentLinkSearch
{
    public function __construct(
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
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
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
