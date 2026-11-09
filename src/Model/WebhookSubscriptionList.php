<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `WebhookSubscriptionSearchResults` (_shared/src/api.core/DTO/WebhookSubscriptionSearchResults.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class WebhookSubscriptionList
{
    /**
     * @param list<WebhookSubscription>|null $webhookSubscriptions
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?array $webhookSubscriptions = null,
        public ?int $count = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            webhookSubscriptions: Read::objects($data, 'webhookSubscriptions', WebhookSubscription::class),
            count: Read::int($data, 'count'),
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
