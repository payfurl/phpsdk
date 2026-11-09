<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `CheckoutTransfer` (api/src/api/Models/CheckoutTransfer.cs), in a request.
 */
final readonly class CheckoutTransfer
{
    public function __construct(
        public float $amount,
        public ?string $account = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'amount' => $this->amount,
            'account' => $this->account,
        ]);
    }
}
