<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `DirectDebitDetails` (_shared/src/api.core/DTO/NewPayToAgreement.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class DirectDebitDetails
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $bsb = null,
        public ?string $accountNumber = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            bsb: Read::string($data, 'bsb'),
            accountNumber: Read::string($data, 'accountNumber'),
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
