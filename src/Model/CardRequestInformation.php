<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `CardRequestInformation` (api/src/api/Models/CardRequestInformation.cs), in a request.
 */
final readonly class CardRequestInformation
{
    /**
     * @param array<string, string>|null $externalThreeDsData
     */
    public function __construct(
        public string $cardNumber,
        public string $expiryDate,
        public ?string $ccv = null,
        public ?string $cardholder = null,
        public ?string $threeDSServerTransID = null,
        public ?array $externalThreeDsData = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'cardNumber' => $this->cardNumber,
            'expiryDate' => $this->expiryDate,
            'ccv' => $this->ccv,
            'cardholder' => $this->cardholder,
            'threeDSServerTransID' => $this->threeDSServerTransID,
            'externalThreeDsData' => Write::map($this->externalThreeDsData),
        ]);
    }
}
