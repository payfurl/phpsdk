<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PaymentMethodSummary` (api/src/api/Models/PaymentMethodSummary.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PaymentMethodSummary
{
    /**
     * @param array<string, string>|null $providerPaymentMethodData
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $paymentMethodId = null,
        public ?string $customerId = null,
        public ?PaymentType $type = null,
        public ?CardData $card = null,
        public ?string $providerId = null,
        public ?string $providerType = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?string $email = null,
        public ?string $vaultId = null,
        public ?\DateTimeImmutable $dateRemoved = null,
        public ?string $providerPaymentMethodId = null,
        public ?array $providerPaymentMethodData = null,
        public ?string $token = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?array $metadata = null,
        public ?string $networkTokenId = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            paymentMethodId: Read::string($data, 'paymentMethodId'),
            customerId: Read::string($data, 'customerId'),
            type: Read::enum($data, 'type', PaymentType::class),
            card: Read::object($data, 'card', CardData::class),
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
            dateAdded: Read::date($data, 'dateAdded'),
            email: Read::string($data, 'email'),
            vaultId: Read::string($data, 'vaultId'),
            dateRemoved: Read::date($data, 'dateRemoved'),
            providerPaymentMethodId: Read::string($data, 'providerPaymentMethodId'),
            providerPaymentMethodData: Read::stringMap($data, 'providerPaymentMethodData'),
            token: Read::string($data, 'token'),
            fallbackPaymentMethodId: Read::string($data, 'fallbackPaymentMethodId'),
            metadata: Read::stringMap($data, 'metadata'),
            networkTokenId: Read::string($data, 'networkTokenId'),
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
