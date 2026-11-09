<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PaymentData` (_shared/src/common/Models/PaymentData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PaymentData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $paymentMethodId = null,
        public ?CardData $card = null,
        public ?PaymentType $type = null,
        public ?string $displayType = null,
        public ?string $email = null,
        public ?BankPaymentData $bankPayment = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            paymentMethodId: Read::string($data, 'paymentMethodId'),
            card: Read::object($data, 'card', CardData::class),
            type: Read::enum($data, 'type', PaymentType::class),
            displayType: Read::string($data, 'displayType'),
            email: Read::string($data, 'email'),
            bankPayment: Read::object($data, 'bankPayment', BankPaymentData::class),
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
