<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewChargeCardLeastCost` (api/src/api/Models/NewChargeCardLeastCost.cs), in a request.
 */
final readonly class NewChargeCardLeastCost
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public float $amount,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $currency = null,
        public ?string $reference = null,
        public ?CardRequestInformation $paymentInformation = null,
        public ?bool $capture = null,
        public ?string $ip = null,
        public ?Order $order = null,
        public ?Address $address = null,
        public ?string $customerCode = null,
        public ?string $invoiceNumber = null,
        public ?Initiator $initiator = null,
        public ?WebhookConfig $webhook = null,
        public ?array $metadata = null,
        public ?string $descriptor = null,
        public ?string $threeDSNotificationUrl = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?Geolocation $geolocation = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'amount' => $this->amount,
            'email' => $this->email,
            'phone' => $this->phone,
            'currency' => $this->currency,
            'reference' => $this->reference,
            'paymentInformation' => $this->paymentInformation?->toArray(),
            'capture' => $this->capture,
            'ip' => $this->ip,
            'order' => $this->order?->toArray(),
            'address' => $this->address?->toArray(),
            'customerCode' => $this->customerCode,
            'invoiceNumber' => $this->invoiceNumber,
            'initiator' => $this->initiator?->value,
            'webhook' => $this->webhook?->toArray(),
            'metadata' => Write::map($this->metadata),
            'descriptor' => $this->descriptor,
            'threeDSNotificationUrl' => $this->threeDSNotificationUrl,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'geolocation' => $this->geolocation?->toArray(),
        ]);
    }
}
