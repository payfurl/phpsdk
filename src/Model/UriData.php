<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `UriData` (_shared/src/api.core/Data/UriData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class UriData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $uri = null,
        public ?string $uriType = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            uri: Read::string($data, 'uri'),
            uriType: Read::string($data, 'uriType'),
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
