<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * A subscription's status. The API's `SubscriptionStatus` (_shared/src/api.core/Data/Subscriptions/SubscriptionStatus.cs).
 */
enum SubscriptionStatus: string
{
    case Unknown = 'Unknown';
    case Complete = 'Complete';
    case Active = 'Active';
    case Cancelled = 'Cancelled';
    case Suspended = 'Suspended';
}
