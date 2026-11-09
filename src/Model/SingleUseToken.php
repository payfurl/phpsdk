<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `SingleUseToken` (api/src/api/Models/SingleUseToken.cs), in a request.
 */
final readonly class SingleUseToken
{
    /**
     * @param array<string, string>|null $providerTokenData
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $providerId,
        public string $providerToken,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $reference = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $ip = null,
        public ?Address $address = null,
        public ?array $providerTokenData = null,
        public ?array $metadata = null,
        public ?string $fallbackPaymentMethodId = null,
        public ?WebhookConfig $webhook = null,
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
            'phone' => $this->phone,
            'reference' => $this->reference,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'ip' => $this->ip,
            'address' => $this->address?->toArray(),
            'providerTokenData' => Write::map($this->providerTokenData),
            'metadata' => Write::map($this->metadata),
            'fallbackPaymentMethodId' => $this->fallbackPaymentMethodId,
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
