<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `FraudOptions` (_shared/src/api.core/DTO/AvailablePaymentTypes.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class FraudOptions
{
    /**
     * @param array<string, string>|null $clientParameters
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $providerId = null,
        public ?string $providerType = null,
        public ?bool $enabled = null,
        public ?bool $stopPaymentOnFailure = null,
        public ?array $clientParameters = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
            enabled: Read::bool($data, 'enabled'),
            stopPaymentOnFailure: Read::bool($data, 'stopPaymentOnFailure'),
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
