<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\Dispute;
use payFURL\Sdk\Model\DisputeList;
use payFURL\Sdk\Model\DisputeSearch;

/**
 * Chargebacks and other disputes on the account's charges (DisputeController).
 */
final class Disputes
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `GET /dispute/{disputeId}` */
    public function get(string $disputeId): Dispute
    {
        return Dispute::fromArray($this->api->get('/dispute/' . ApiClient::id($disputeId, 'disputeId')));
    }

    /** `GET /dispute` */
    public function search(DisputeSearch $search = new DisputeSearch()): DisputeList
    {
        return DisputeList::fromArray($this->api->get('/dispute', $search->toQuery()));
    }
}
