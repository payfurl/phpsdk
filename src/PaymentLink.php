<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');
require_once(__DIR__ . '/tools/CaseConverter.php');

/**
 * @copyright PayFURL
 */
class PaymentLink
{
    private array $validSearchKeys = ['AddedAfter', 'AddedBefore', 'Limit', 'SortBy', 'Skip'];

    /**
     * @throws ResponseException
     */
    public function Create($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Title']);

        $sourceParams = [
            'Title' => 1,
            'Amount' => 1,
            'Currency' => 1,
            'AllowedPaymentTypes' => 1,
            'Description' => 1,
            'Image' => 1,
            'ConfirmationMessage' => 1,
            'RedirectUrl' => 1,
            'CallToAction' => 1,
            'LimitPayments' => 1,
        ];
        $data = array_intersect_key($params, $sourceParams);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_link', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function Single($tokenId)
    {
        $url = '/payment_link/' . urlencode($tokenId);

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function Search($parameters)
    {
        $params = CaseConverter::convertKeysToPascalCase($parameters);
        try {
            $url = '/payment_link' . UrlTools::CreateQueryString($parameters, $this->validSearchKeys);
        } catch (\Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }
}
