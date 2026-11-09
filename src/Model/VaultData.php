<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `VaultData` (api/src/api/Models/VaultData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class VaultData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $vaultId = null,
        public ?\DateTimeImmutable $createdDate = null,
        public ?\DateTimeImmutable $expireDate = null,
        public ?string $maskedCardNumber = null,
        public ?bool $hasCvv = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            vaultId: Read::string($data, 'vaultId'),
            createdDate: Read::date($data, 'createdDate'),
            expireDate: Read::date($data, 'expireDate'),
            maskedCardNumber: Read::string($data, 'maskedCardNumber'),
            hasCvv: Read::bool($data, 'hasCvv'),
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
