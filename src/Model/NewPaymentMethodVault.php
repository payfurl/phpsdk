<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewPaymentMethodVault` (api/src/api/Models/NewPaymentMethodVault.cs), in a request.
 */
final readonly class NewPaymentMethodVault
{
    public function __construct(
        public string $providerId,
        public string $paymentMethodId,
        public ?string $ccv = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?WebhookConfig $webhook = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'providerId' => $this->providerId,
            'paymentMethodId' => $this->paymentMethodId,
            'ccv' => $this->ccv,
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
