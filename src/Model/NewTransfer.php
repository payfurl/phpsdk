<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewTransfer` (api/src/api/Models/TransferItem.cs), in a request.
 */
final readonly class NewTransfer
{
    public function __construct(
        public string $account,
        public float $amount,
        public ?string $currency = null,
        public ?string $message = null,
        public ?string $reference = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'account' => $this->account,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'message' => $this->message,
            'reference' => $this->reference,
        ]);
    }
}
