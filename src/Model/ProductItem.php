<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `ProductItem` (api/src/api/Models/Address.cs), in a request.
 */
final readonly class ProductItem
{
    public function __construct(
        public ?string $productCode = null,
        public ?string $commodityCode = null,
        public ?string $description = null,
        public ?int $quantity = null,
        public ?string $unitOfMeasure = null,
        public ?float $amount = null,
        public ?float $taxAmount = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'productCode' => $this->productCode,
            'commodityCode' => $this->commodityCode,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unitOfMeasure' => $this->unitOfMeasure,
            'amount' => $this->amount,
            'taxAmount' => $this->taxAmount,
        ]);
    }
}
