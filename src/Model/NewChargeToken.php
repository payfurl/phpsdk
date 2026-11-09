<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewChargeToken` (api/src/api/Models/NewChargeToken.cs), in a request.
 */
final readonly class NewChargeToken
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public float $amount,
        public string $token,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $currency = null,
        public ?string $reference = null,
        public ?CheckoutTransfer $transfer = null,
        public ?bool $capture = null,
        public ?string $ip = null,
        public ?Address $address = null,
        public ?Order $order = null,
        public ?string $customerCode = null,
        public ?string $invoiceNumber = null,
        public ?Initiator $initiator = null,
        public ?WebhookConfig $webhook = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?array $metadata = null,
        public ?string $descriptor = null,
        public ?string $threeDSNotificationUrl = null,
        public ?Geolocation $geolocation = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'amount' => $this->amount,
            'token' => $this->token,
            'email' => $this->email,
            'phone' => $this->phone,
            'currency' => $this->currency,
            'reference' => $this->reference,
            'transfer' => $this->transfer?->toArray(),
            'capture' => $this->capture,
            'ip' => $this->ip,
            'address' => $this->address?->toArray(),
            'order' => $this->order?->toArray(),
            'customerCode' => $this->customerCode,
            'invoiceNumber' => $this->invoiceNumber,
            'initiator' => $this->initiator?->value,
            'webhook' => $this->webhook?->toArray(),
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'metadata' => Write::map($this->metadata),
            'descriptor' => $this->descriptor,
            'threeDSNotificationUrl' => $this->threeDSNotificationUrl,
            'geolocation' => $this->geolocation?->toArray(),
        ]);
    }
}
