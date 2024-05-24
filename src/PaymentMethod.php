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
        ArrayTools::ValidateKeys($params, ['PayerName', 'PayerPayIdDetails' => ['PayId', 'PayIdType'], 'Description', 'MaximumAmount', 'ProviderId']);

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
    public function CreateWithSingleUseToken($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'ProviderToken']);

        $data = [];
        $data['ProviderId'] = $params['ProviderId'];
        $data['ProviderToken'] = $params['ProviderToken'];
        if (array_key_exists("ProviderTokenData", $params)) {
            $data['ProviderTokenData'] = $params['ProviderTokenData'];
        }
        if (array_key_exists("Metadata", $params)) {
            $data['Metadata'] = $params['Metadata'];
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/provider_single_use_token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithProviderToken($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'ProviderToken']);

        $sourceParams = [
            'ProviderId' => 1,
            'ProviderToken' => 1,
            'ProviderTokenData' => 1,
            'Metadata' => 1,
            'Email' => 1,
            'Verify' => 1,
        ];
        $data = array_intersect_key($params, $sourceParams);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/provider_token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function RemovePaymentMethod($params)
    {
        ArrayTools::ValidateKeys($params, ['PaymentMethodId']);

        return HttpWrapper::CallApi('/payment_method/' . urlencode($params['PaymentMethodId']), 'DELETE', '');
    }

    /**
     * @throws ResponseException
     */
    public function CreatePaymentMethodWithBankAccount($params)
    {
        ArrayTools::ValidateKeys($params, ['ProviderId', 'LastName', 'BankPaymentInformation' => ['BankCode', 'AccountNumber', 'AccountName']]);

        $data =  $this->BuildBankPaymentInformationJson($params);
        $data['BankPaymentInformation'] = $this->BuildBankPaymentInformationJson($params['BankPaymentInformation'] ?? []);
        $data['ProviderId'] = $params['ProviderId'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/bank_account', 'POST', json_encode($data));
    }

    private function BuildPaymentInformationJson($params): array
    {
        $sourceParams = ['CardNumber' => 1, 'ExpiryDate' => 1, 'Ccv' => 1, 'Cardholder' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildBankPaymentInformationJson($params): array
    {
        $sourceParams = ['BankCode' => 1, 'AccountNumber' => 1, 'AccountName' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildBankPaymentInformationJson($params): array
    {
        $sourceParams = ['FirstName' => 1, 'LastName' => 1];
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
