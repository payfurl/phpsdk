<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;
use payFURL\Sdk\Model\Settlement;

/**
 * Gateway settlements of the account's charges (SettlementController).
 */
final class Settlements
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /**
     * `GET /settlements`: from `$startDate` up to `$endDate` (now when null), by date: the time of day is ignored.
     *
     * @return list<Settlement>
     */
    public function search(\DateTimeInterface $startDate, ?\DateTimeInterface $endDate = null): array
    {
        return Read::list(
            $this->api->get('/settlements', Write::query(['startDate' => $startDate->format('Y-m-d'), 'endDate' => $endDate?->format('Y-m-d')])),
            Settlement::class,
        );
    }
}
