<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `UpdatePaymentMethod` (api/src/api/Models/UpdatePaymentMethod.cs), in a request.
 */
final readonly class UpdatePaymentMethod
{
    /**
     * @param array<string, string>|null $metadata
     */
    public function __construct(
        public UpdatePaymentMethodCardRequestInformation $card,
        public ?array $metadata = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'card' => $this->card->toArray(),
            'metadata' => Write::map($this->metadata),
        ]);
    }
}
