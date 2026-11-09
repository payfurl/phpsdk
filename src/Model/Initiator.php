<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * Who started a charge: the customer at checkout, or the merchant (a stored card, a subscription). The API's `Initiator` (api/src/api/Models/Initiator.cs).
 */
enum Initiator: string
{
    case Customer = 'CUSTOMER';
    case Merchant = 'MERCHANT';
}
