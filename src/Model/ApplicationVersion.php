<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ApplicationVersion` (api/src/api/Models/Version.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ApplicationVersion
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $version = null,
        public ?\DateTimeImmutable $released = null,
        public ?string $region = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            version: Read::string($data, 'version'),
            released: Read::date($data, 'released'),
            region: Read::string($data, 'region'),
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
