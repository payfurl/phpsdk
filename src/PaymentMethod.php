<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');

/**
 * @copyright PayFURL
 */
class PaymentMethod
{
    private array $validSearchKeys = [
        'AddedAfter', 'AddedBefore', 'ProviderId', 'CustomerId', 'PaymentType',
        'Search', 'SortBy', 'Limit'];

    /**
     * @throws ResponseException
     */
    public function Checkout($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'Amount']);

        $sourceParams = [
            'ProviderId' => 1,
            'Amount' => 1,
            'Currency' => 1,
            'Reference' => 1,
            'Transfer' => 1,
            'Options' => 1,
            'Customer' => 1,
            'ShippingAmount' => 1,
            'TaxAmount' => 1,
            'Items' => 1,
            'Ip' => 1,
        ];
        $data = array_intersect_key($params, $sourceParams);

        if (isset($params['Transfer'])) {
            $extraParams = ['Account' => 1, 'Amount' => 1];
            $data['Transfer'] = array_intersect_key($params, $extraParams);
        }
        if (isset($params['Customer'])) {
            $extraParams = ['FirstName' => 1, 'LastName' => 1, 'Email' => 1, 'Phone' => 1];
            $data['Customer'] = array_intersect_key($params, $extraParams);
            if (isset($params['Customer']['ShippingAddress'])) {
                $extraParams = ['Name' => 1, 'AddressLine1' => 1, 'AddressLine2' => 1, 'Suburb' => 1, 'State' => 1, 'Postcode' => 1, 'CountryCode' => 1];
                $data['Customer']['ShippingAddress'] = array_intersect_key($params, $extraParams);
            }
            if (isset($params['Customer']['BillingAddress'])) {
                $extraParams = ['Name' => 1, 'AddressLine1' => 1, 'AddressLine2' => 1, 'Suburb' => 1, 'State' => 1, 'Postcode' => 1, 'CountryCode' => 1];
                $data['Customer']['BillingAddress'] = array_intersect_key($params, $extraParams);
            }
            if (isset($params['Items'])) {
                $data['Items'] = array_map(fn($value) => [
                    'Name' => $value['Name'] ?? null,
                    'Reference' => $value['Reference'] ?? null,
                    'Quantity' => $value['Quantity'] ?? null,
                    'Amount' => $value['Amount'] ?? null,
                    'ItemUrl' => $value['ItemUrl'] ?? null,
                    'ImageUrl' => $value['ImageUrl'] ?? null,
                ], $params['Items']);
            }
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/checkout', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function Search($params)
    {
        try {
            $url = '/payment_method' . UrlTools::CreateQueryString($params, $this->validSearchKeys);
        } catch (\Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function Single($params)
    {
        ArrayTools::ValidateKeys($params, ['PaymentMethodId']);

        try {
            $url = '/payment_method/' . urlencode($params['PaymentMethodId']);
        } catch (\Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function CreatePaymentMethodWithCard($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'PaymentInformation' => ['CardNumber', 'ExpiryDate', 'Ccv']]);

        $data = [];
        $data = array_merge($data, $this->BuildVaultInformationJson($params));
        $data = array_merge($data, $this->BuildIpInformationJson($params));
        $data['PaymentInformation'] = $this->BuildPaymentInformationJson($params['PaymentInformation'] ?? []);
        $data['ProviderId'] = $params['ProviderId'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/card', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreatePaymentMethodWithVault($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'PaymentMethodId']);

        $sourceParams = ['ProviderId' => 1, 'PaymentMethodId' => 1, 'Ccv' => 1];
        $data = array_intersect_key($params, $sourceParams);;

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/vault', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithPayTo($params)
    {
        ArrayTools::ValidateKeys($params, ['PayToAgreement']);

        $data = $this->BuildPayToAgreementJson($params);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/payto', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithToken($params)
    {
        ArrayTools::ValidateKeys($params, ['Token']);

        $sourceParams = ['Token' => 1, 'Ip' => 1];
        $data = array_intersect_key($params, $sourceParams);;

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function RemovePaymentMethod($params)
    {
        ArrayTools::ValidateKeys($params, ['PaymentMethodId']);

        return HttpWrapper::CallApi('/payment_method/' . urlencode($params['PaymentMethodId']), 'DELETE', '');
    }

    private function BuildPaymentInformationJson($params): array
    {
        $sourceParams = ['CardNumber' => 1, 'ExpiryDate' => 1, 'Ccv' => 1, 'Cardholder' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildIpInformationJson($params): array
    {
        $sourceParams = ['Ip' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildVaultInformationJson($params): array
    {
        $sourceParams = ['VaultCard' => 1, 'VaultExpireDate' => 1, 'VaultExpireSeconds' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildPayToAgreementJson($params)
    {
        $sourceParams = ['PayerName' => 1, 'Description' => 1, 'MaximumAmount' => 1, 'ProviderId' => 1, 'Ip' => 1];
        $data = array_intersect_key($params, $sourceParams);
        if (isset($params['PayerPayIdDetails'])) {
            $detailsParams = ['PayId' => 1, 'PayIdType' => 1];
            $data['PayerPayIdDetails'] = array_intersect_key($params['PayerPayIdDetails'], $detailsParams);
        }
        return $data;
    }
}
