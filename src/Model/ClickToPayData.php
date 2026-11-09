<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ClickToPayData` (api/src/api/Models/ClickToPayData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ClickToPayData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?Address $billingAddress = null,
        public ?ClickToPayCardData $cardData = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            billingAddress: Read::object($data, 'billingAddress', Address::class),
            cardData: Read::object($data, 'cardData', ClickToPayCardData::class),
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
