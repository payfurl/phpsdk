<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PaymentMethodData` (api/src/api/Models/PaymentMethodData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PaymentMethodData
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
        public ?BankPaymentData $bankPayment = null,
        public ?string $providerId = null,
        public ?string $providerType = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?string $email = null,
        public ?CustomerDataSummary $customer = null,
        public ?ProviderSummary $provider = null,
        public ?PayToSummary $payTo = null,
        public ?NetworkTokenSummary $networkToken = null,
        public ?string $vaultId = null,
        public ?string $payToStatus = null,
        public ?string $ip = null,
        public ?\DateTimeImmutable $dateRemoved = null,
        public ?string $providerPaymentMethodId = null,
        public ?array $providerPaymentMethodData = null,
        public ?string $token = null,
        public ?string $networkTokenId = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?array $metadata = null,
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
            bankPayment: Read::object($data, 'bankPayment', BankPaymentData::class),
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
            dateAdded: Read::date($data, 'dateAdded'),
            email: Read::string($data, 'email'),
            customer: Read::object($data, 'customer', CustomerDataSummary::class),
            provider: Read::object($data, 'provider', ProviderSummary::class),
            payTo: Read::object($data, 'payTo', PayToSummary::class),
            networkToken: Read::object($data, 'networkToken', NetworkTokenSummary::class),
            vaultId: Read::string($data, 'vaultId'),
            payToStatus: Read::string($data, 'payToStatus'),
            ip: Read::string($data, 'ip'),
            dateRemoved: Read::date($data, 'dateRemoved'),
            providerPaymentMethodId: Read::string($data, 'providerPaymentMethodId'),
            providerPaymentMethodData: Read::stringMap($data, 'providerPaymentMethodData'),
            token: Read::string($data, 'token'),
            networkTokenId: Read::string($data, 'networkTokenId'),
            fallbackPaymentMethodId: Read::string($data, 'fallbackPaymentMethodId'),
            metadata: Read::stringMap($data, 'metadata'),
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
