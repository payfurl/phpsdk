<?php

namespace payFURL\Sdk;

use Exception;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');

/**
 * @copyright PayFURL
 */
class Batch
{
    private array $validSearchKeys = [
        'Description', 'AddedAfter', 'AddedBefore', 'Limit', 'Skip',
    ];

    /**
     * @throws ResponseException
     */
    public function CreateTransactionWithPaymentMethod($params)
    {
        ArrayTools::ValidateKeys($params, ['Count', 'Batch']);

        $data = $this->BuildCreateTransactionJson($params);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/batch/transaction/payment_method', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function GetBatch($params)
    {
        ArrayTools::ValidateKeys($params, ['BatchId']);

        $url = '/batch/' . urlencode($params['BatchId']);

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function GetBatchStatus($params)
    {
        ArrayTools::ValidateKeys($params, ['BatchId']);

        $url = '/batch/' . urlencode($params['BatchId']) . '/status';

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function Search($params)
    {
        try {
            $url = '/batch' . UrlTools::CreateQueryString($params, $this->validSearchKeys);
        } catch (Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    private function BuildCreateTransactionJson($params): array
    {
        $sourceParams = ['Count' => 1, 'Description' => 1, 'Batch' => 1];
        return array_intersect_key($params, $sourceParams);
    }

}
