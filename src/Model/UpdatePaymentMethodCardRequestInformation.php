<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `UpdatePaymentMethodCardRequestInformation` (api/src/api/Models/UpdatePaymentMethod.cs), in a request.
 */
final readonly class UpdatePaymentMethodCardRequestInformation
{
    public function __construct(
        public ?string $expiryDate = null,
        public ?string $cardholder = null,
        public ?string $cardNumber = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'expiryDate' => $this->expiryDate,
            'cardholder' => $this->cardholder,
            'cardNumber' => $this->cardNumber,
        ]);
    }
}
