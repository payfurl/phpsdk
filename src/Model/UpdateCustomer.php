<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `UpdateCustomer` (api/src/api/Models/UpdateCustomer.cs), in a request.
 */
final readonly class UpdateCustomer
{
    public function __construct(
        public ?string $email = null,
        public ?string $phone = null,
        public ?Address $address = null,
        public ?string $defaultPaymentMethodId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address?->toArray(),
            'defaultPaymentMethodId' => $this->defaultPaymentMethodId,
        ]);
    }
}
