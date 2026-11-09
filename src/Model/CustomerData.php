<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `CustomerData` (api/src/api/Models/CustomerData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class CustomerData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $customerId = null,
        public ?string $reference = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?PaymentMethodSummary $defaultPaymentMethod = null,
        public ?string $ip = null,
        public ?\DateTimeImmutable $dateRemoved = null,
        public ?Address $address = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            customerId: Read::string($data, 'customerId'),
            reference: Read::string($data, 'reference'),
            firstName: Read::string($data, 'firstName'),
            lastName: Read::string($data, 'lastName'),
            email: Read::string($data, 'email'),
            phone: Read::string($data, 'phone'),
            dateAdded: Read::date($data, 'dateAdded'),
            defaultPaymentMethod: Read::object($data, 'defaultPaymentMethod', PaymentMethodSummary::class),
            ip: Read::string($data, 'ip'),
            dateRemoved: Read::date($data, 'dateRemoved'),
            address: Read::object($data, 'address', Address::class),
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
