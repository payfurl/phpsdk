<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;

/**
 * The API's `SubscriptionEnd` (api/src/api/Models/Subscriptions/SubscriptionEnd.cs), in a request and response.
 */
final readonly class SubscriptionEnd
{
    public function __construct(
        public ?float $amount = null,
        public ?int $count = null,
        public ?\DateTimeInterface $date = null,
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: Read::float($data, 'amount'),
            count: Read::int($data, 'count'),
            date: Read::date($data, 'date'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'amount' => $this->amount,
            'count' => $this->count,
            'date' => Write::date($this->date),
        ]);
    }
}
