<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ProviderData` (api/src/api/Models/ProviderData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class Provider
{
    /**
     * @param array<string, string>|null $authenticationParameters
     * @param array<string, string>|null $additionalParameters
     * @param list<ProviderCostData>|null $costData
     * @param list<ProviderVisibilityData>|null $hideConfiguration
     * @param list<ProviderVisibilityData>|null $requireConfiguration
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $providerId = null,
        public ?string $type = null,
        public ?string $name = null,
        public ?string $environment = null,
        public ?array $authenticationParameters = null,
        public ?array $additionalParameters = null,
        public ?string $providerCountry = null,
        public ?array $costData = null,
        public ?array $hideConfiguration = null,
        public ?array $requireConfiguration = null,
        public ?string $fallbackProviderId = null,
        public ?string $currency = null,
        public ?string $maxCapability = null,
        public ?bool $hasPartialRefund = null,
        public ?ProviderInfo $fallbackProvider = null,
        public ?bool $hasThreeDsCapability = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            providerId: Read::string($data, 'providerId'),
            type: Read::string($data, 'type'),
            name: Read::string($data, 'name'),
            environment: Read::string($data, 'environment'),
            authenticationParameters: Read::stringMap($data, 'authenticationParameters'),
            additionalParameters: Read::stringMap($data, 'additionalParameters'),
            providerCountry: Read::string($data, 'providerCountry'),
            costData: Read::objects($data, 'costData', ProviderCostData::class),
            hideConfiguration: Read::objects($data, 'hideConfiguration', ProviderVisibilityData::class),
            requireConfiguration: Read::objects($data, 'requireConfiguration', ProviderVisibilityData::class),
            fallbackProviderId: Read::string($data, 'fallbackProviderId'),
            currency: Read::string($data, 'currency'),
            maxCapability: Read::string($data, 'maxCapability'),
            hasPartialRefund: Read::bool($data, 'hasPartialRefund'),
            fallbackProvider: Read::object($data, 'fallbackProvider', ProviderInfo::class),
            hasThreeDsCapability: Read::bool($data, 'hasThreeDsCapability'),
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
