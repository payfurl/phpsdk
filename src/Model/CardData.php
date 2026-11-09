<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `CardData` (_shared/src/common/Models/CardData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class CardData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $cardNumber = null,
        public ?string $expiryDate = null,
        public ?string $cardholder = null,
        public ?string $type = null,
        public ?string $cardType = null,
        public ?string $cardIin = null,
        public ?bool $ccvCheck = null,
        public ?string $threeDSServerTransID = null,
        public ?IinData $iinData = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            cardNumber: Read::string($data, 'cardNumber'),
            expiryDate: Read::string($data, 'expiryDate'),
            cardholder: Read::string($data, 'cardholder'),
            type: Read::string($data, 'type'),
            cardType: Read::string($data, 'cardType'),
            cardIin: Read::string($data, 'cardIin'),
            ccvCheck: Read::bool($data, 'ccvCheck'),
            threeDSServerTransID: Read::string($data, 'threeDSServerTransID'),
            iinData: Read::object($data, 'iinData', IinData::class),
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
