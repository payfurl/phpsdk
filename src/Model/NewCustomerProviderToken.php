<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewCustomerProviderToken` (api/src/api/Models/NewCustomerProviderToken.cs), in a request.
 */
final readonly class NewCustomerProviderToken
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
        public ?bool $verify = null,
        public ?PaymentType $type = null,
        public ?array $metadata = null,
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
            'verify' => $this->verify,
            'type' => $this->type?->value,
            'metadata' => Write::map($this->metadata),
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
