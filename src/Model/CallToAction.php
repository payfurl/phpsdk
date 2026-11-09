<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * A payment link's button. The API's `CallToAction` (_shared/src/common/Models/CallToAction.cs).
 */
enum CallToAction: string
{
    case Pay = 'Pay';
    case Donate = 'Donate';
}
