<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\BatchData;
use payFURL\Sdk\Model\BatchList;
use payFURL\Sdk\Model\BatchSearch;
use payFURL\Sdk\Model\BatchStatus;
use payFURL\Sdk\Model\NewTransactionPaymentMethod;

/**
 * Batches of charges on saved payment methods (BatchController).
 */
final class Batches
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /batch/transaction/payment_method`: `$batch->batch` is the CSV of charges. */
    public function createWithPaymentMethods(NewTransactionPaymentMethod $batch): BatchStatus
    {
        return BatchStatus::fromArray($this->api->post('/batch/transaction/payment_method', $batch->toArray()));
    }

    /** `GET /batch/{batchId}`: the batch with its results. */
    public function get(string $batchId): BatchData
    {
        return BatchData::fromArray($this->api->get('/batch/' . ApiClient::id($batchId, 'batchId')));
    }

    /** `GET /batch/{batchId}/status`: the batch's progress, without its results. */
    public function status(string $batchId): BatchStatus
    {
        return BatchStatus::fromArray($this->api->get('/batch/' . ApiClient::id($batchId, 'batchId') . '/status'));
    }

    /** `GET /batch` */
    public function search(BatchSearch $search = new BatchSearch()): BatchList
    {
        return BatchList::fromArray($this->api->get('/batch', $search->toQuery()));
    }
}
