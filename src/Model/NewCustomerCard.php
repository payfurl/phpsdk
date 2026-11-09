<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewCustomerCard` (api/src/api/Models/NewCustomerCard.cs), in a request.
 */
final readonly class NewCustomerCard
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $providerId,
        public CardRequestInformation $paymentInformation,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $reference = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $ip = null,
        public ?Address $address = null,
        public ?bool $vaultCard = null,
        public ?\DateTimeInterface $vaultExpireDate = null,
        public ?int $vaultExpireSeconds = null,
        public ?bool $skipExpiryDateValidation = null,
        public ?array $metadata = null,
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
            'email' => $this->email,
            'phone' => $this->phone,
            'reference' => $this->reference,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'ip' => $this->ip,
            'address' => $this->address?->toArray(),
            'vaultCard' => $this->vaultCard,
            'vaultExpireDate' => Write::date($this->vaultExpireDate),
            'vaultExpireSeconds' => $this->vaultExpireSeconds,
            'skipExpiryDateValidation' => $this->skipExpiryDateValidation,
            'metadata' => Write::map($this->metadata),
            'webhook' => $this->webhook?->toArray(),
            'createNetworkToken' => $this->createNetworkToken,
            'verify' => $this->verify,
        ]);
    }
}
