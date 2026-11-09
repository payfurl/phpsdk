<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ChargeData` (_shared/src/common/Models/ChargeData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ChargeData
{
    /**
     * @param list<Refund>|null $refunds
     * @param list<FailureData>|null $failedAttempts
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $chargeId = null,
        public ?string $providerChargeId = null,
        public ?float $amount = null,
        public ?string $providerId = null,
        public ?string $reference = null,
        public ?PaymentData $paymentInformation = null,
        public ?string $customerId = null,
        public ?TransactionStatus $status = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?\DateTimeImmutable $successDate = null,
        public ?\DateTimeImmutable $voidDate = null,
        public ?float $refundedAmount = null,
        public ?float $estimatedCost = null,
        public ?string $estimatedCostCurrency = null,
        public ?string $currency = null,
        public ?array $refunds = null,
        public ?array $failedAttempts = null,
        public ?CustomerDataSummary $customer = null,
        public ?ProviderSummary $provider = null,
        public ?bool $threeDsVerified = null,
        public ?float $authorisationAmount = null,
        public ?string $initiator = null,
        public ?string $ip = null,
        public ?GeolocationInfo $ipInfo = null,
        public ?VisaInstallmentsInfo $visaInstallments = null,
        public ?string $descriptor = null,
        public ?string $threeDsRedirectUrl = null,
        public ?string $paymentTokenId = null,
        public ?string $subscriptionId = null,
        public ?array $metadata = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            chargeId: Read::string($data, 'chargeId'),
            providerChargeId: Read::string($data, 'providerChargeId'),
            amount: Read::float($data, 'amount'),
            providerId: Read::string($data, 'providerId'),
            reference: Read::string($data, 'reference'),
            paymentInformation: Read::object($data, 'paymentInformation', PaymentData::class),
            customerId: Read::string($data, 'customerId'),
            status: Read::enum($data, 'status', TransactionStatus::class),
            dateAdded: Read::date($data, 'dateAdded'),
            successDate: Read::date($data, 'successDate'),
            voidDate: Read::date($data, 'voidDate'),
            refundedAmount: Read::float($data, 'refundedAmount'),
            estimatedCost: Read::float($data, 'estimatedCost'),
            estimatedCostCurrency: Read::string($data, 'estimatedCostCurrency'),
            currency: Read::string($data, 'currency'),
            refunds: Read::objects($data, 'refunds', Refund::class),
            failedAttempts: Read::objects($data, 'failedAttempts', FailureData::class),
            customer: Read::object($data, 'customer', CustomerDataSummary::class),
            provider: Read::object($data, 'provider', ProviderSummary::class),
            threeDsVerified: Read::bool($data, 'threeDsVerified'),
            authorisationAmount: Read::float($data, 'authorisationAmount'),
            initiator: Read::string($data, 'initiator'),
            ip: Read::string($data, 'ip'),
            ipInfo: Read::object($data, 'ipInfo', GeolocationInfo::class),
            visaInstallments: Read::object($data, 'visaInstallments', VisaInstallmentsInfo::class),
            descriptor: Read::string($data, 'descriptor'),
            threeDsRedirectUrl: Read::string($data, 'threeDsRedirectUrl'),
            paymentTokenId: Read::string($data, 'paymentTokenId'),
            subscriptionId: Read::string($data, 'subscriptionId'),
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
