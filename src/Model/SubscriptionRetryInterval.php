<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * The unit of a subscription's retry frequency. The API's `SubscriptionRetryInterval` (_shared/src/api.core/Data/Subscriptions/SubscriptionRetryInterval.cs).
 */
enum SubscriptionRetryInterval: string
{
    case Hour = 'Hour';
    case Day = 'Day';
}
