<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;

/**
 * The API's `SubscriptionRetryPolicy` (api/src/api/Models/Subscriptions/SubscriptionRetryPolicy.cs), in a request and response.
 */
final readonly class SubscriptionRetryPolicy
{
    public function __construct(
        public ?int $maximum = null,
        public ?SubscriptionRetryInterval $interval = null,
        public ?int $frequency = null,
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            maximum: Read::int($data, 'maximum'),
            interval: Read::enum($data, 'interval', SubscriptionRetryInterval::class),
            frequency: Read::int($data, 'frequency'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'maximum' => $this->maximum,
            'interval' => $this->interval?->value,
            'frequency' => $this->frequency,
        ]);
    }
}
