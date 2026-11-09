<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;

/**
 * The API's `Address` (api/src/api/Models/Address.cs), in a request and response.
 */
final readonly class Address
{
    public function __construct(
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $postalCode = null,
        public ?string $state = null,
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            line1: Read::string($data, 'line1'),
            line2: Read::string($data, 'line2'),
            city: Read::string($data, 'city'),
            country: Read::string($data, 'country'),
            postalCode: Read::string($data, 'postalCode'),
            state: Read::string($data, 'state'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'country' => $this->country,
            'postalCode' => $this->postalCode,
            'state' => $this->state,
        ]);
    }
}
