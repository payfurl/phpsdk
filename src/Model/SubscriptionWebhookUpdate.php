<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `SubscriptionWebhookUpdate` (api/src/api/Models/Subscriptions/SubscriptionWebhookUpdate.cs), in a request.
 */
final readonly class SubscriptionWebhookUpdate
{
    public function __construct(
        public ?string $url = null,
        public ?string $authorization = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'url' => $this->url,
            'authorization' => $this->authorization,
        ]);
    }
}
