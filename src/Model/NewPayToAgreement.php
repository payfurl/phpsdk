<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewPayToAgreement` (api/src/api/Models/NewPayToAgreement.cs), in a request.
 */
final readonly class NewPayToAgreement
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $payerName,
        public PayIdDetails $payerPayIdDetails,
        public string $description,
        public int $maximumAmount,
        public string $providerId,
        public ?string $notificationUrl = null,
        public ?string $ip = null,
        public ?bool $setDefault = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?WebhookConfig $webhook = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'payerName' => $this->payerName,
            'payerPayIdDetails' => $this->payerPayIdDetails->toArray(),
            'description' => $this->description,
            'maximumAmount' => $this->maximumAmount,
            'providerId' => $this->providerId,
            'notificationUrl' => $this->notificationUrl,
            'ip' => $this->ip,
            'setDefault' => $this->setDefault,
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
