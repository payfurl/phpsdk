<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ClickToPayOptions` (_shared/src/api.core/DTO/AvailablePaymentTypes.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ClickToPayOptions
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $dpaId = null,
        public ?string $dpaName = null,
        public ?bool $useFpan = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dpaId: Read::string($data, 'dpaId'),
            dpaName: Read::string($data, 'dpaName'),
            useFpan: Read::bool($data, 'useFpan'),
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
