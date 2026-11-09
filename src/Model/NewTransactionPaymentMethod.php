<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewTransactionPaymentMethod` (api/src/api/Models/Batch/NewTransactionPaymentMethod.cs), in a request.
 */
final readonly class NewTransactionPaymentMethod
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public int $count,
        public string $description,
        public string $batch,
        public ?WebhookConfig $webhook = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'count' => $this->count,
            'description' => $this->description,
            'batch' => $this->batch,
            'webhook' => $this->webhook?->toArray(),
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
