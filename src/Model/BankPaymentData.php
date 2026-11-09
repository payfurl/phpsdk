<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `BankPaymentData` (_shared/src/common/Models/BankPaymentData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class BankPaymentData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $bankCode = null,
        public ?string $accountNumber = null,
        public ?string $accountName = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            bankCode: Read::string($data, 'bankCode'),
            accountNumber: Read::string($data, 'accountNumber'),
            accountName: Read::string($data, 'accountName'),
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
