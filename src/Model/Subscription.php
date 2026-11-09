<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `Subscription` (api/src/api/Models/Subscriptions/Subscription.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class Subscription
{
    /**
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $subscriptionId = null,
        public ?string $paymentMethodId = null,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?SubscriptionInterval $interval = null,
        public ?int $frequency = null,
        public ?\DateTimeImmutable $createdDate = null,
        public ?\DateTimeImmutable $startDate = null,
        public ?SubscriptionEnd $endAfter = null,
        public ?SubscriptionRetryPolicy $retry = null,
        public ?WebhookConfig $webhook = null,
        public ?SubscriptionStatus $status = null,
        public ?array $metadata = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            subscriptionId: Read::string($data, 'subscriptionId'),
            paymentMethodId: Read::string($data, 'paymentMethodId'),
            amount: Read::float($data, 'amount'),
            currency: Read::string($data, 'currency'),
            interval: Read::enum($data, 'interval', SubscriptionInterval::class),
            frequency: Read::int($data, 'frequency'),
            createdDate: Read::date($data, 'createdDate'),
            startDate: Read::date($data, 'startDate'),
            endAfter: Read::object($data, 'endAfter', SubscriptionEnd::class),
            retry: Read::object($data, 'retry', SubscriptionRetryPolicy::class),
            webhook: Read::object($data, 'webhook', WebhookConfig::class),
            status: Read::enum($data, 'status', SubscriptionStatus::class),
            metadata: Read::stringMap($data, 'metadata'),
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
