<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * A charge's status (`ChargeData::$status`). The API's `TransactionStatus` (_shared/src/api.core/Data/Transaction.cs).
 */
enum TransactionStatus: string
{
    case Pending = 'PENDING';
    case Failed = 'FAILED';
    case Success = 'SUCCESS';
    case PartialRefund = 'PARTIAL_REFUND';
    case Refund = 'REFUND';
    case PendingCheckout = 'PENDING_CHECKOUT';
    case Authorise = 'AUTHORISE';
    case AuthoriseCancelled = 'AUTHORISE_CANCELLED';
}
