<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `VisaInstallmentsInfo` (_shared/src/api.core/DTO/NewPaymentToken.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class VisaInstallmentsInfo
{
    /**
     * @param list<VisaInstallmentsTermsAndConditions>|null $visTermsAndConditions
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $providerId = null,
        public ?string $visPlanId = null,
        public ?int $visAcceptedTAndCVersion = null,
        public ?string $visPlanIdRef = null,
        public ?string $visPlanName = null,
        public ?int $visNumberOfInstallments = null,
        public ?float $visTotalFees = null,
        public ?float $visTotalPlanCost = null,
        public ?string $visInstallmentFrequency = null,
        public ?array $visTermsAndConditions = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            providerId: Read::string($data, 'providerId'),
            visPlanId: Read::string($data, 'visPlanId'),
            visAcceptedTAndCVersion: Read::int($data, 'visAcceptedTAndCVersion'),
            visPlanIdRef: Read::string($data, 'visPlanIdRef'),
            visPlanName: Read::string($data, 'visPlanName'),
            visNumberOfInstallments: Read::int($data, 'visNumberOfInstallments'),
            visTotalFees: Read::float($data, 'visTotalFees'),
            visTotalPlanCost: Read::float($data, 'visTotalPlanCost'),
            visInstallmentFrequency: Read::string($data, 'visInstallmentFrequency'),
            visTermsAndConditions: Read::objects($data, 'visTermsAndConditions', VisaInstallmentsTermsAndConditions::class),
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
