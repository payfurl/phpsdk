<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewWebhookSubscription` (api/src/api/Models/NewWebhookSubscription.cs), in a request.
 */
final readonly class NewWebhookSubscription
{
    /**
     * @param list<WebhookType> $types
     */
    public function __construct(
        public string $url,
        public array $types,
        public ?string $authorization = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'url' => $this->url,
            'types' => Write::enums($this->types),
            'authorization' => $this->authorization,
        ]);
    }
}
