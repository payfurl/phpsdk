<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `GooglePayProviderInfo` (_shared/src/api.core/DTO/GooglePayProviderInfo.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class GooglePayProviderInfo
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $gateway = null,
        public ?string $googlePayGatewayMerchantId = null,
        public ?string $googlePayMerchantId = null,
        public ?string $googlePayMerchantName = null,
        public ?string $providerId = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            gateway: Read::string($data, 'gateway'),
            googlePayGatewayMerchantId: Read::string($data, 'googlePayGatewayMerchantId'),
            googlePayMerchantId: Read::string($data, 'googlePayMerchantId'),
            googlePayMerchantName: Read::string($data, 'googlePayMerchantName'),
            providerId: Read::string($data, 'providerId'),
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
