<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `TokenData` (api/src/api/Models/TokenData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class TokenData
{
    /**
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $tokenId = null,
        public ?string $accountId = null,
        public ?CardData $card = null,
        public ?ProviderSummary $provider = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?\DateTimeImmutable $dateUsed = null,
        public ?string $payToStatus = null,
        public ?array $metadata = null,
        public ?ClickToPayData $clickToPay = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            tokenId: Read::string($data, 'tokenId'),
            accountId: Read::string($data, 'accountId'),
            card: Read::object($data, 'card', CardData::class),
            provider: Read::object($data, 'provider', ProviderSummary::class),
            dateAdded: Read::date($data, 'dateAdded'),
            dateUsed: Read::date($data, 'dateUsed'),
            payToStatus: Read::string($data, 'payToStatus'),
            metadata: Read::stringMap($data, 'metadata'),
            clickToPay: Read::object($data, 'clickToPay', ClickToPayData::class),
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
