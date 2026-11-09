<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `CreatePaymentLink` (api/src/api/Models/PaymentLink/CreatePaymentLink.cs), in a request.
 */
final readonly class CreatePaymentLink
{
    /**
     * @param list<PaymentType>|null $allowedPaymentTypes
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public string $title,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?array $allowedPaymentTypes = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?string $confirmationMessage = null,
        public ?string $redirectUrl = null,
        public ?CallToAction $callToAction = null,
        public ?int $limitPayments = null,
        public ?string $invoiceId = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'title' => $this->title,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'allowedPaymentTypes' => Write::enums($this->allowedPaymentTypes),
            'description' => $this->description,
            'image' => $this->image,
            'confirmationMessage' => $this->confirmationMessage,
            'redirectUrl' => $this->redirectUrl,
            'callToAction' => $this->callToAction?->value,
            'limitPayments' => $this->limitPayments,
            'invoiceId' => $this->invoiceId,
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
