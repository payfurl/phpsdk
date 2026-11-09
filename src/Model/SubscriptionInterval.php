<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * How often a subscription charges, with its frequency. The API's `SubscriptionInterval` (_shared/src/api.core/Data/Subscriptions/SubscriptionInterval.cs).
 */
enum SubscriptionInterval: string
{
    case Day = 'Day';
    case Week = 'Week';
    case Month = 'Month';
    case Year = 'Year';
}
