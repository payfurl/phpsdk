<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `Order` (api/src/api/Models/Address.cs), in a request.
 */
final readonly class Order
{
    /**
     * @param list<ProductItem>|null $items
     */
    public function __construct(
        public ?string $orderNumber = null,
        public ?float $freightAmount = null,
        public ?float $dutyAmount = null,
        public ?array $items = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'orderNumber' => $this->orderNumber,
            'freightAmount' => $this->freightAmount,
            'dutyAmount' => $this->dutyAmount,
            'items' => Write::objects($this->items),
        ]);
    }
}
