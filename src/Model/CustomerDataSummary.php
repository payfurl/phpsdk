<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `CustomerDataSummary` (_shared/src/common/Models/CustomerSummary.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class CustomerDataSummary
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
        public ?\DateTimeImmutable $dateRemoved = null,
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
            dateRemoved: Read::date($data, 'dateRemoved'),
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
