<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFURL
 */
class PaymentMethod
{
    private $ValidSearchKeys = array("addedafter", "addedbefore", "providerid", "customerid", "paymenttype",
        "search", "sortby", "limit");

    public function Checkout($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ProviderId", "Amount"));

        $Data = [];

        if ($Params != NULL)
        {
            foreach ($Params as $Key => $Value)
            {
                $Data[$Key] = $Value;
            }
        }
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/payment_method/checkout", "POST", json_encode($Data));
    }

    public function Search($Params)
    {
        try
        {
            $url = "/payment_method" . UrlTools::CreateQueryString($Params, $this->ValidSearchKeys);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Single($Params)
    {
        try
        {
            $url = "/payment_method/" . urlencode($Params["paymentMethodId"]);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function CreatePaymentMethodWithCard($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ProviderId", "CardNumber", "ExpiryDate", "Ccv"));

        $Data = [
            'providerId' => $Params["ProviderId"],
            'paymentInformation' => [
                'cardNumber' => $Params["CardNumber"],
                'expiryDate' => $Params["ExpiryDate"],
                'ccv' => $Params["Ccv"],
                'cardholder' => $Params["Cardholder"] ?? null
            ],
            'vaultCard' => $Params["VaultCard"] ?? null,
            'VaultExpireDate' => $Params["VaultExpireDate"] ?? null,
            'VaultExpireSeconds' => $Params["VaultExpireSeconds"] ?? null
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/payment_method/card", "POST", json_encode($Data));
    }

    public function CreatePaymentMethodWithVault($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ProviderId", "VaultId", "PaymentMethodId"));

        $Data = [
            'providerId' => $Params["ProviderId"],
            'vaultId' => $Params["VaultId"],
            'paymentMethodId' => $Params["PaymentMethodId"],
            'ccv' => $Params["Ccv"] ?? null,
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/payment_method/vault", "POST", json_encode($Data));
    }
}