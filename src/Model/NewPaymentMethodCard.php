<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewPaymentMethodCard` (api/src/api/Models/NewPaymentMethodCard.cs), in a request.
 */
final readonly class NewPaymentMethodCard
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $providerId,
        public CardRequestInformation $paymentInformation,
        public ?bool $vaultCard = null,
        public ?\DateTimeInterface $vaultExpireDate = null,
        public ?int $vaultExpireSeconds = null,
        public ?string $ip = null,
        public ?bool $setDefault = null,
        public ?bool $skipExpiryDateValidation = null,
        public ?array $metadata = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?WebhookConfig $webhook = null,
        public ?bool $createNetworkToken = null,
        public ?bool $verify = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'providerId' => $this->providerId,
            'paymentInformation' => $this->paymentInformation->toArray(),
            'vaultCard' => $this->vaultCard,
            'vaultExpireDate' => Write::date($this->vaultExpireDate),
            'vaultExpireSeconds' => $this->vaultExpireSeconds,
            'ip' => $this->ip,
            'setDefault' => $this->setDefault,
            'skipExpiryDateValidation' => $this->skipExpiryDateValidation,
            'metadata' => Write::map($this->metadata),
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
            'createNetworkToken' => $this->createNetworkToken,
            'verify' => $this->verify,
        ]);
    }
}
