<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');
require_once(__DIR__ . '/tools/CaseConverter.php');

/**
 * @copyright PayFURL
 */
class PaymentMethod
{
    private array $validSearchKeys = [
        'AddedAfter', 'AddedBefore', 'ProviderId', 'CustomerId', 'PaymentType',
        'CardType', 'Search', 'SortBy', 'SortOrder', 'Limit', 'Skip', 'IncludeRemoved'];

    /**
     * @throws ResponseException
     */
    public function Search($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
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
        $params = CaseConverter::convertKeysToPascalCase($params);
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
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ProviderId', 'PaymentInformation' => ['CardNumber', 'ExpiryDate', 'Ccv']]);

        $data = [];
        $data = array_merge($data, $this->BuildVaultInformationJson($params));
        $data = array_merge($data, $this->BuildIpInformationJson($params));
        $data['PaymentInformation'] = $this->BuildPaymentInformationJson($params['PaymentInformation'] ?? []);
        $data['ProviderId'] = $params['ProviderId'];
        if (array_key_exists('SkipExpiryDateValidation', $params)) {
            $data['SkipExpiryDateValidation'] = $params['SkipExpiryDateValidation'];
        }
        if (array_key_exists('Metadata', $params)) {
            $data['Metadata'] = $params['Metadata'];
        }
        if (array_key_exists('FallbackPaymentMethodId', $params)) {
            $data['FallbackPaymentMethodId'] = $params['FallbackPaymentMethodId'];
        }
        if (array_key_exists('CreateNetworkToken', $params)) {
            $data['CreateNetworkToken'] = $params['CreateNetworkToken'];
        }
        if (array_key_exists('Verify', $params)) {
            $data['Verify'] = $params['Verify'];
        }
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/card', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreatePaymentMethodWithVault($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ProviderId', 'PaymentMethodId']);

        $sourceParams = ['ProviderId' => 1, 'PaymentMethodId' => 1, 'Ccv' => 1, 'FallbackPaymentMethodId' => 1];
        $data = array_intersect_key($params, $sourceParams);;
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/vault', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithPayTo($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['PayerName', 'PayerPayIdDetails' => ['PayId', 'PayIdType'], 'Description', 'MaximumAmount', 'ProviderId']);

        $data = $this->BuildPayToAgreementJson($params);
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/payto', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithToken($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Token']);

        $sourceParams = ['Token' => 1, 'Ip' => 1, 'SetDefault' => 1, 'Metadata' => 1, 'FallbackPaymentMethodId' => 1];
        $data = array_intersect_key($params, $sourceParams);;
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithSingleUseToken($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ProviderId', 'ProviderToken']);

        $data = $this->BuildCustomerInformationJson($params);
        $data['ProviderId'] = $params['ProviderId'];
        $data['ProviderToken'] = $params['ProviderToken'];
        if (array_key_exists("ProviderTokenData", $params)) {
            $data['ProviderTokenData'] = $params['ProviderTokenData'];
        }
        if (array_key_exists("Metadata", $params)) {
            $data['Metadata'] = $params['Metadata'];
        }
        if (array_key_exists("FallbackPaymentMethodId", $params)) {
            $data['FallbackPaymentMethodId'] = $params['FallbackPaymentMethodId'];
        }
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/provider_single_use_token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithProviderToken($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ProviderId', 'ProviderToken']);

        $sourceParams = [
            'ProviderId' => 1,
            'ProviderToken' => 1,
            'ProviderTokenData' => 1,
            'Metadata' => 1,
            'Email' => 1,
            'Verify' => 1,
            'FallbackPaymentMethodId' => 1,
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
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['PaymentMethodId']);

        $queryParams = [];
        if (array_key_exists('GatewayDelete', $params)) {
            $queryParams['GatewayDelete'] = $params['GatewayDelete'];
        }

        $url = '/payment_method/' . urlencode($params['PaymentMethodId']) . UrlTools::CreateQueryString($queryParams);

        return HttpWrapper::CallApi($url, 'DELETE', '');
    }

    /**
     * @throws ResponseException
     */
    public function CreatePaymentMethodWithBankAccount($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ProviderId', 'BankPaymentInformation' => ['BankCode', 'AccountNumber', 'AccountName']]);

        $data = [];
        $data['BankPaymentInformation'] = $this->BuildBankPaymentInformationJson($params['BankPaymentInformation'] ?? []);
        $data['ProviderId'] = $params['ProviderId'];
        if (array_key_exists('FirstName', $params)) {
            $data['FirstName'] = $params['FirstName'];
        }
        if (array_key_exists('LastName', $params)) {
            $data['LastName'] = $params['LastName'];
        }
        if (array_key_exists('Metadata', $params)) {
            $data['Metadata'] = $params['Metadata'];
        }
        if (array_key_exists('SetDefault', $params)) {
            $data['SetDefault'] = $params['SetDefault'];
        }
        if (array_key_exists('FallbackPaymentMethodId', $params)) {
            $data['FallbackPaymentMethodId'] = $params['FallbackPaymentMethodId'];
        }
        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/bank_account', 'POST', json_encode($data));
    }

    public function UpdatePaymentMethod($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['PaymentMethodId', 'Card']);

        $data = [];
        $data['Card'] = $this->BuildUpdatePaymentMethodInformationJson($params['Card'] ?? []);
        if (array_key_exists('Metadata', $params)) {
            $data['Metadata'] = $params['Metadata'];
        }
        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_method/' . urlencode($params['PaymentMethodId']), 'PUT', json_encode($data));
    }

    private function BuildPaymentInformationJson($params): array
    {
        $sourceParams = ['CardNumber' => 1, 'ExpiryDate' => 1, 'Ccv' => 1, 'Cardholder' => 1, 'ThreeDSServerTransID' => 1, 'ExternalThreeDsData' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildBankPaymentInformationJson($params): array
    {
        $sourceParams = ['BankCode' => 1, 'AccountNumber' => 1, 'AccountName' => 1];
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
        $sourceParams = ['PayerName' => 1, 'Description' => 1, 'MaximumAmount' => 1, 'ProviderId' => 1, 'Ip' => 1, 'SetDefault' => 1, 'FallbackPaymentMethodId' => 1, 'Metadata' => 1];
        $data = array_intersect_key($params, $sourceParams);
        if (isset($params['PayerPayIdDetails'])) {
            $detailsParams = ['PayId' => 1, 'PayIdType' => 1];
            $data['PayerPayIdDetails'] = array_intersect_key($params['PayerPayIdDetails'], $detailsParams);
        }
        return $data;
    }

    private function BuildUpdatePaymentMethodInformationJson($params): array
    {
        $sourceParams = ['ExpiryDate' => 1, 'Cardholder' => 1, 'CardNumber' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildWebhookConfiguration($params): array
    {
        $sourceParams = ['Url' => 1, 'Authorization' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildCustomerInformationJson($params): array
    {
        $sourceParams = ['Reference' => 1, 'FirstName' => 1, 'LastName' => 1, 'Email' => 1, 'Phone' => 1, 'Ip' => 1];
        $data = array_intersect_key($params, $sourceParams);

        if (array_key_exists('Address', $params)) {
            $addressParams = ['Line1' => 1, 'Line2' => 1, 'City' => 1, 'Country' => 1, 'PostalCode' => 1, 'State' => 1];
            $data['Address'] = array_intersect_key($params['Address'], $addressParams);
        }

        return $data;
    }
}
