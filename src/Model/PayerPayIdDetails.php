<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `PayerPayIdDetails` (_shared/src/common/Models/PayToSummary.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class PayerPayIdDetails
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $payId = null,
        public ?string $payIdType = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            payId: Read::string($data, 'payId'),
            payIdType: Read::string($data, 'payIdType'),
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
