<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewBankPayment` (api/src/api/Models/NewBankPayment.cs), in a request.
 */
final readonly class NewBankPayment
{
    public function __construct(
        public string $bankCode,
        public string $accountNumber,
        public string $accountName,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'bankCode' => $this->bankCode,
            'accountNumber' => $this->accountNumber,
            'accountName' => $this->accountName,
        ]);
    }
}
