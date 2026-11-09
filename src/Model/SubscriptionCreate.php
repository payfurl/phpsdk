<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `SubscriptionCreate` (api/src/api/Models/Subscriptions/SubscriptionCreate.cs), in a request.
 */
final readonly class SubscriptionCreate
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $paymentMethodId,
        public float $amount,
        public string $currency,
        public SubscriptionInterval $interval,
        public int $frequency,
        public ?\DateTimeInterface $startDate = null,
        public ?SubscriptionEnd $endAfter = null,
        public ?SubscriptionRetryPolicy $retry = null,
        public ?WebhookConfig $webhook = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'paymentMethodId' => $this->paymentMethodId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'interval' => $this->interval->value,
            'frequency' => $this->frequency,
            'startDate' => Write::date($this->startDate),
            'endAfter' => $this->endAfter?->toArray(),
            'retry' => $this->retry?->toArray(),
            'webhook' => $this->webhook?->toArray(),
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
