<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `PayIdDetails` (api/src/api/Models/NewPayToAgreement.cs), in a request.
 */
final readonly class PayIdDetails
{
    public function __construct(
        public string $payId,
        public string $payIdType,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'payId' => $this->payId,
            'payIdType' => $this->payIdType,
        ]);
    }
}
