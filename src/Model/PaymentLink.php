<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PaymentLinkModel` (api/src/api/Models/PaymentLink/PaymentLinkModel.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PaymentLink
{
    /**
     * @param list<string>|null $allowedPaymentTypes as the API sent them: the browser SDK's names (`card`, ...)
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $url = null,
        public ?string $paymentLinkId = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?string $accountId = null,
        public ?string $publicKey = null,
        public ?string $title = null,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?array $allowedPaymentTypes = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?string $confirmationMessage = null,
        public ?string $redirectUrl = null,
        public ?CallToAction $callToAction = null,
        public ?int $limitPayments = null,
        public ?int $countPayments = null,
        public ?string $invoiceId = null,
        public ?array $metadata = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: Read::string($data, 'url'),
            paymentLinkId: Read::string($data, 'paymentLinkId'),
            dateAdded: Read::date($data, 'dateAdded'),
            accountId: Read::string($data, 'accountId'),
            publicKey: Read::string($data, 'publicKey'),
            title: Read::string($data, 'title'),
            amount: Read::float($data, 'amount'),
            currency: Read::string($data, 'currency'),
            allowedPaymentTypes: Read::strings($data, 'allowedPaymentTypes'),
            description: Read::string($data, 'description'),
            image: Read::string($data, 'image'),
            confirmationMessage: Read::string($data, 'confirmationMessage'),
            redirectUrl: Read::string($data, 'redirectUrl'),
            callToAction: Read::enum($data, 'callToAction', CallToAction::class),
            limitPayments: Read::int($data, 'limitPayments'),
            countPayments: Read::int($data, 'countPayments'),
            invoiceId: Read::string($data, 'invoiceId'),
            metadata: Read::stringMap($data, 'metadata'),
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
