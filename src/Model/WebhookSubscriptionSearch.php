<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The query of GET /webhook/subscription (WebhookSubscriptionController.Search). Every filter is optional; `limit` and `skip` page through the results.
 */
final readonly class WebhookSubscriptionSearch
{
    public function __construct(
        public ?\DateTimeInterface $addedAfter = null,
        public ?\DateTimeInterface $addedBefore = null,
        public ?string $id = null,
        public ?WebhookType $type = null,
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
            'addedAfter' => $this->addedAfter,
            'addedBefore' => $this->addedBefore,
            'id' => $this->id,
            'type' => $this->type,
            'sortBy' => $this->sortBy,
            'limit' => $this->limit,
            'skip' => $this->skip,
        ]);
    }
}
