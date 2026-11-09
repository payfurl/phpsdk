<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `UpdateSubscriptionStatus` (api/src/api/Models/UpdateSubscriptionStatus.cs), in a request.
 */
final readonly class UpdateSubscriptionStatus
{
    public function __construct(
        public SubscriptionStatus $status,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'status' => $this->status->value,
        ]);
    }
}
