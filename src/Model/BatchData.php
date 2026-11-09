<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `BatchData` (api/src/api/Models/Batch/BatchData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class BatchData
{
    /**
     * @param array<string, string>|null $metadata
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $batchId = null,
        public ?int $count = null,
        public ?int $success = null,
        public ?int $failure = null,
        public ?string $description = null,
        public ?string $status = null,
        public ?float $progress = null,
        public ?string $results = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?array $metadata = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            batchId: Read::string($data, 'batchId'),
            count: Read::int($data, 'count'),
            success: Read::int($data, 'success'),
            failure: Read::int($data, 'failure'),
            description: Read::string($data, 'description'),
            status: Read::string($data, 'status'),
            progress: Read::float($data, 'progress'),
            results: Read::string($data, 'results'),
            dateAdded: Read::date($data, 'dateAdded'),
            metadata: Read::stringMap($data, 'metadata'),
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
