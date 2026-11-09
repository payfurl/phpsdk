<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ApplePayProviderInfo` (_shared/src/api.core/DTO/ApplePayProviderInfo.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ApplePayProviderInfo
{
    /**
     * @param array<string, string>|null $clientParameters
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $lineItemLabel = null,
        public ?string $merchantIdentifier = null,
        public ?string $domainName = null,
        public ?string $displayName = null,
        public ?string $countryCode = null,
        public ?string $providerId = null,
        public ?string $providerType = null,
        public ?array $clientParameters = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            lineItemLabel: Read::string($data, 'lineItemLabel'),
            merchantIdentifier: Read::string($data, 'merchantIdentifier'),
            domainName: Read::string($data, 'domainName'),
            displayName: Read::string($data, 'displayName'),
            countryCode: Read::string($data, 'countryCode'),
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
            clientParameters: Read::stringMap($data, 'clientParameters'),
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
