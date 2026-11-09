<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `SubscriptionUpdate` (api/src/api/Models/Subscriptions/SubscriptionUpdate.cs), in a request.
 */
final readonly class SubscriptionUpdate
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public float $amount,
        public string $currency,
        public SubscriptionInterval $interval,
        public int $frequency,
        public ?SubscriptionEnd $endAfter = null,
        public ?SubscriptionRetryPolicy $retry = null,
        public ?SubscriptionWebhookUpdate $webhook = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'amount' => $this->amount,
            'currency' => $this->currency,
            'interval' => $this->interval->value,
            'frequency' => $this->frequency,
            'endAfter' => $this->endAfter?->toArray(),
            'retry' => $this->retry?->toArray(),
            'webhook' => $this->webhook?->toArray(),
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
