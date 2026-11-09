<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `CustomerList` (api/src/api/Models/CustomerList.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class CustomerList
{
    /**
     * @param list<CustomerData>|null $customers
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?int $limit = null,
        public ?int $skip = null,
        public ?int $count = null,
        public ?array $customers = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            limit: Read::int($data, 'limit'),
            skip: Read::int($data, 'skip'),
            count: Read::int($data, 'count'),
            customers: Read::objects($data, 'customers', CustomerData::class),
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
