<?php

namespace payFURL\Sdk;

use Exception;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');
require_once(__DIR__ . '/tools/CaseConverter.php');

/**
 * @copyright PayFURL
 */
class Charge
{
    private array $validSearchKeys = [
        'Reference', 'ProviderId', 'AmountGreaterThan', 'AmountLessThan', 'Currency',
        'CustomerId', 'Status', 'AddedAfter', 'AddedBefore', 'PaymentMethodId', 'PaymentType',
        'CardType', 'CardNumber', 'Cardholder',
        'BatchId', 'SubscriptionId', 'SortBy', 'SortOrder', 'Limit', 'Skip',
    ];

    /**
     * @throws ResponseException
     */
    public function CreateWithCard($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'ProviderId', 'PaymentInformation' => ['CardNumber', 'ExpiryDate', 'Ccv']]);

        $data = $this->BuildCreateChargeJson($params);

        $data['ProviderId'] = $params['ProviderId'];
        $data['PaymentInformation'] = $this->BuildPaymentInformationJson($params['PaymentInformation'] ?? []);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/card', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithCardLeastCost($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'PaymentInformation' => ['CardNumber', 'ExpiryDate', 'Ccv']]);

        $data = $this->BuildCreateChargeJson($params);
        $data['PaymentInformation'] = $this->BuildPaymentInformationJson($params['PaymentInformation'] ?? []);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/card/least_cost', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithCustomer($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'CustomerId']);

        $data = $this->BuildCreateChargeJson($params);
        $data['CustomerId'] = $params['CustomerId'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/customer', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithPaymentMethod($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'PaymentMethodId']);

        $data = $this->BuildCreateChargeJson($params);
        $data['PaymentMethodId'] = $params['PaymentMethodId'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/payment_method', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithToken($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Token']);

        $data = $this->BuildCreateChargeJson($params);
        $data['Token'] = $params['Token'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithApplePay($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, [
            'ProviderId',
            'Amount',
            'Token' => [
                'PaymentData' => [
                    'Data',
                    'Signature',
                    'Header' => ['PublicKeyHash', 'EphemeralPublicKey', 'TransactionId'],
                    'Version',
                ],
                'PaymentMethod' => ['DisplayName', 'Network', 'Type'],
            ],
        ]);

        $data = $this->BuildCreateChargeJson($params);
        $data['Token'] = $this->BuildAppleTokenJson($params['Token'] ?? []);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/token/apple', 'POST', json_encode($data), ['ProviderId' => $params['ProviderId']]);
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithNetworkToken($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'ProviderId', 'NetworkTokenId']);

        $data = $this->BuildCreateChargeJson($params);
        $data['ProviderId'] = $params['ProviderId'];
        $data['NetworkTokenId'] = $params['NetworkTokenId'];

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/network_token', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function Single($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ChargeId']);

        $url = '/charge/' . urlencode($params['ChargeId']);

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function Refund($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ChargeId']);

        $queryParams = [];
        if (isset($params['Amount']) && $params['Amount'] > 0) {
            $queryParams['Amount'] = $params['Amount'];
        }
        if (isset($params['Comment'])) {
            $queryParams['Comment'] = $params['Comment'];
        }

        $url = '/charge/' . urlencode($params['ChargeId']) . UrlTools::CreateQueryString($queryParams);

        return HttpWrapper::CallApi($url, 'DELETE', '');
    }

    /**
     * @throws ResponseException
     */
    public function Search($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        try {
            $url = '/charge' . UrlTools::CreateQueryString($params, $this->validSearchKeys);
        } catch (Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function Capture($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ChargeId']);

        $url = '/charge/' . urlencode($params['ChargeId']) . '/capture';

        $data = [];
        if (isset($params['Amount']) && $params['Amount'] > 0) {
            $data['Amount'] = $params['Amount'];
        }
        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi($url, 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function Void($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['ChargeId']);

        $url = '/charge/' . urlencode($params['ChargeId']) . '/void';

        return HttpWrapper::CallApi($url, 'DELETE', '');
    }

    /**
     * @throws ResponseException
     */
    public function CreateWithBankAccount($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Amount', 'ProviderId', 'BankPaymentInformation' => ['BankCode', 'AccountNumber', 'AccountName']]);

        $data = $this->BuildCreateChargeJson($params);

        $data['ProviderId'] = $params['ProviderId'];
        $data['BankPaymentInformation'] = $this->BuildBankPaymentInformationJson($params['BankPaymentInformation'] ?? []);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/charge/bank_account', 'POST', json_encode($data));
    }

    private function BuildCreateChargeJson($params): array
    {
        $sourceParams = ['Amount' => 1, 'Currency' => 1, 'Reference' => 1, 'Capture' => 1, 'Ip' => 1];
        $data = array_intersect_key($params, $sourceParams);

        if (array_key_exists('Address', $params)) {
            $sourceParams = ['Line1' => 1, 'Line2' => 1, 'City' => 1, 'Country' => 1, 'PostalCode' => 1, 'State' => 1];
            $data['Address'] = array_intersect_key($params['Address'], $sourceParams);
        }

        if (array_key_exists('Order', $params)) {
            $sourceParams = ['OrderNumber' => 1, 'FreightAmount' => 1, 'DutyAmount' => 1, 'Country' => 1, 'PostalCode' => 1, 'State' => 1];
            $data['Order'] = array_intersect_key($params['Order'], $sourceParams);
            if (isset($params['Order']['Items'])) {
                $data['Order']['Items'] = array_map(fn($value) => [
                    'ProductCode' => $value['ProductCode'] ?? null,
                    'CommodityCode' => $value['CommodityCode'] ?? null,
                    'Description' => $value['Description'] ?? null,
                    'Quantity' => $value['Quantity'] ?? null,
                    'UnitOfMeasure' => $value['UnitOfMeasure'] ?? null,
                    'Amount' => $value['Amount'] ?? null,
                    'TaxAmount' => $value['TaxAmount'] ?? null,
                ], $params['Order']['Items']);

            }
        }
        if (array_key_exists('CustomerCode', $params)) {
            $data['CustomerCode'] = $params['CustomerCode'];
        }
        if (array_key_exists('InvoiceNumber', $params)) {
            $data['InvoiceNumber'] = $params['InvoiceNumber'];
        }

        if (array_key_exists('Initiator', $params)) {
            $data['Initiator'] = $params['Initiator'];
        }

        if (array_key_exists('Descriptor', $params)) {
            $data['Descriptor'] = $params['Descriptor'];
        }

        if (array_key_exists('ThreeDSNotificationUrl', $params)) {
            $data['ThreeDSNotificationUrl'] = $params['ThreeDSNotificationUrl'];
        }

        if (array_key_exists('FirstName', $params)) {
            $data['FirstName'] = $params['FirstName'];
        }

        if (array_key_exists('LastName', $params)) {
            $data['LastName'] = $params['LastName'];
        }

        if (array_key_exists('Email', $params)) {
            $data['Email'] = $params['Email'];
        }

        if (array_key_exists('Phone', $params)) {
            $data['Phone'] = $params['Phone'];
        }

        if (array_key_exists('UserAgent', $params)) {
            $data['UserAgent'] = $params['UserAgent'];
        }

        if (isset($params['Webhook'])) {
            $data['Webhook'] = $this->BuildWebhookConfiguration($params['Webhook'] ?? []);
        }

        if (isset($params['Metadata'])) {
            $data['Metadata'] = $params['Metadata'];
        }

        if (isset($params['Geolocation'])) {
            $sourceParams = ['Longitude' => 1, 'Latitude' => 1];
            $data['Geolocation'] = array_intersect_key($params['Geolocation'], $sourceParams);
        }

        if (isset($params['Recurring'])) {
            $data['Recurring'] = $params['Recurring'];
        }

        if (isset($params['Transfer'])) {
            $data['Transfer'] = $this->BuildTransferJson($params['Transfer'] ?? []);
        }

        return $data;
    }

    private function BuildPaymentInformationJson($params): array
    {
        $sourceParams = ['CardNumber' => 1, 'ExpiryDate' => 1, 'Ccv' => 1, 'Cardholder' => 1, 'ThreeDSServerTransID' => 1, 'ExternalThreeDsData' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildWebhookConfiguration($params): array
    {
        $sourceParams = ['Url' => 1, 'Authorization' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildBankPaymentInformationJson($params): array
    {
        $sourceParams = ['BankCode' => 1, 'AccountNumber' => 1, 'AccountName' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildTransferJson($params): array
    {
        $sourceParams = ['Account' => 1, 'Amount' => 1];
        return array_intersect_key($params, $sourceParams);
    }

    private function BuildAppleTokenJson($params): array
    {
        $data = [];

        if (isset($params['PaymentData'])) {
            $sourceParams = ['Data' => 1, 'Signature' => 1, 'Version' => 1];
            $data['PaymentData'] = array_intersect_key($params['PaymentData'], $sourceParams);
            if (isset($params['PaymentData']['Header'])) {
                $headerParams = ['PublicKeyHash' => 1, 'EphemeralPublicKey' => 1, 'TransactionId' => 1];
                $data['PaymentData']['Header'] = array_intersect_key($params['PaymentData']['Header'], $headerParams);
            }
        }

        if (isset($params['PaymentMethod'])) {
            $methodParams = ['DisplayName' => 1, 'Network' => 1, 'Type' => 1];
            $data['PaymentMethod'] = array_intersect_key($params['PaymentMethod'], $methodParams);
        }

        return $data;
    }
}
