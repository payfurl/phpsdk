<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `Refund` (_shared/src/api.core/Data/Transaction.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class Refund
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?float $amount = null,
        public ?string $comment = null,
        public ?string $userId = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?string $gatewayRefundId = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: Read::float($data, 'amount'),
            comment: Read::string($data, 'comment'),
            userId: Read::string($data, 'userId'),
            dateAdded: Read::date($data, 'dateAdded'),
            gatewayRefundId: Read::string($data, 'gatewayRefundId'),
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
