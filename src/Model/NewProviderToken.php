<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewProviderToken` (api/src/api/Models/NewProviderToken.cs), in a request.
 */
final readonly class NewProviderToken
{
    /**
     * @param array<string, string>|null $providerTokenData
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $providerId,
        public string $providerToken,
        public ?string $email = null,
        public ?array $providerTokenData = null,
        public ?array $metadata = null,
        public ?bool $verify = null,
        public ?PaymentType $type = null,
        public ?string $fallbackPaymentMethodId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'providerId' => $this->providerId,
            'providerToken' => $this->providerToken,
            'email' => $this->email,
            'providerTokenData' => Write::map($this->providerTokenData),
            'metadata' => Write::map($this->metadata),
            'verify' => $this->verify,
            'type' => $this->type?->value,
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
        ]);
    }
}
