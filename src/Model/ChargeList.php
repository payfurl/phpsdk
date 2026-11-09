<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ChargeList` (api/src/api/Models/ChargeList.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ChargeList
{
    /**
     * @param list<ChargeData>|null $charges
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?int $limit = null,
        public ?int $skip = null,
        public ?int $count = null,
        public ?array $charges = null,
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
            charges: Read::objects($data, 'charges', ChargeData::class),
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
