<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Model\NewTransferGroup;
use payFURL\Sdk\Model\TransferData;
use payFURL\Sdk\Model\TransferList;
use payFURL\Sdk\Model\TransferSearch;

/**
 * Transfers to connected accounts (TransferController).
 */
final class Transfers
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /**
     * `POST /transfer`: one transfer per item of `$transfers->transfers`, linked to a charge or not.
     *
     * @return list<TransferData>
     */
    public function create(NewTransferGroup $transfers): array
    {
        return Read::list($this->api->post('/transfer', $transfers->toArray()), TransferData::class);
    }

    /** `GET /transfer/{transferId}` */
    public function get(string $transferId): TransferData
    {
        return TransferData::fromArray($this->api->get('/transfer/' . ApiClient::id($transferId, 'transferId')));
    }

    /** `GET /transfer` */
    public function search(TransferSearch $search = new TransferSearch()): TransferList
    {
        return TransferList::fromArray($this->api->get('/transfer', $search->toQuery()));
    }
}
