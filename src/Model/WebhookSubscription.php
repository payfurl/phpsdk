<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `WebhookSubscriptionData` (_shared/src/api.core/DTO/WebhookSubscriptionData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class WebhookSubscription
{
    /**
     * @param list<WebhookType>|null $types
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $webhookSubscriptionId = null,
        public ?string $accountId = null,
        public ?string $url = null,
        public ?string $authorization = null,
        public ?array $types = null,
        public ?\DateTimeImmutable $createdDate = null,
        public ?\DateTimeImmutable $dateRemoved = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            webhookSubscriptionId: Read::string($data, 'webhookSubscriptionId'),
            accountId: Read::string($data, 'accountId'),
            url: Read::string($data, 'url'),
            authorization: Read::string($data, 'authorization'),
            types: Read::enums($data, 'types', WebhookType::class),
            createdDate: Read::date($data, 'createdDate'),
            dateRemoved: Read::date($data, 'dateRemoved'),
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
