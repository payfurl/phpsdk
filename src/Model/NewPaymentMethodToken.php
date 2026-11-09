<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewPaymentMethodToken` (api/src/api/Models/NewPaymentMethodToken.cs), in a request.
 */
final readonly class NewPaymentMethodToken
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $token,
        public ?string $ip = null,
        public ?bool $setDefault = null,
        public ?array $metadata = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?WebhookConfig $webhook = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'token' => $this->token,
            'ip' => $this->ip,
            'setDefault' => $this->setDefault,
            'metadata' => Write::map($this->metadata),
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
