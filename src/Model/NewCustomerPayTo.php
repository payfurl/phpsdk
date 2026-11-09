<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewCustomerPayTo` (api/src/api/Models/NewCustomerPayTo.cs), in a request.
 */
final readonly class NewCustomerPayTo
{
    public function __construct(
        public NewPayToAgreement $payToAgreement,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $reference = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $ip = null,
        public ?Address $address = null,
        public ?WebhookConfig $webhook = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'payToAgreement' => $this->payToAgreement->toArray(),
            'email' => $this->email,
            'phone' => $this->phone,
            'reference' => $this->reference,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'ip' => $this->ip,
            'address' => $this->address?->toArray(),
            'webhook' => $this->webhook?->toArray(),
        ]);
    }
}
