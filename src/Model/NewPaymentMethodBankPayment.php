<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewPaymentMethodBankPayment` (api/src/api/Models/NewPaymentMethodBankPayment.cs), in a request.
 */
final readonly class NewPaymentMethodBankPayment
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $providerId,
        public NewBankPayment $bankPaymentInformation,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?array $metadata = null,
        public ?bool $setDefault = null,
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
            'bankPaymentInformation' => $this->bankPaymentInformation->toArray(),
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'metadata' => Write::map($this->metadata),
            'setDefault' => $this->setDefault,
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
