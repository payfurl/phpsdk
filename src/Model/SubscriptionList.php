<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `SubscriptionList` (api/src/api/Controllers/SubscriptionController.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class SubscriptionList
{
    /**
     * @param list<Subscription>|null $subscriptions
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?int $count = null,
        public ?array $subscriptions = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            count: Read::int($data, 'count'),
            subscriptions: Read::objects($data, 'subscriptions', Subscription::class),
            raw: $data,
        );
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }
}
