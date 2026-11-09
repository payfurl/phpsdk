<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ClickToPayCardData` (api/src/api/Models/ClickToPayCardData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ClickToPayCardData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $panBin = null,
        public ?string $panLastFour = null,
        public ?string $scheme = null,
        public ?string $paymentCardType = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            panBin: Read::string($data, 'panBin'),
            panLastFour: Read::string($data, 'panLastFour'),
            scheme: Read::string($data, 'scheme'),
            paymentCardType: Read::string($data, 'paymentCardType'),
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
