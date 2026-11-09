<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * What a webhook subscription receives. The API's `WebhookType` (_shared/src/common/Models/WebhookType.cs).
 */
enum WebhookType: string
{
    case Transaction = 'Transaction';
    case Batch = 'Batch';
    case Subscription = 'Subscription';
    case Customer = 'Customer';
    case PaymentMethod = 'PaymentMethod';
    case Dispute = 'Dispute';
}
