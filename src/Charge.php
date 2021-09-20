<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFurl
 */
class Charge
{
    private $ValidSearchKeys = array("reference", "providerId", "amountgreateryhan", "amountlessthan",
        "customerid", "status", "addedafter", "addedbefore", "sortby", "limit", "skip");

    public function CreateWithCard($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "CardNumber", "ExpiryDate", "Ccv"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['providerId'] = $Params["ProviderId"];
        $Data['paymentInformation'] = [
            'cardNumber' => $Params["CardNumber"],
            'expiryDate' => $Params["ExpiryDate"],
            'ccv' => $Params["Ccv"],
            'cardholder' => $Params["Cardholder"]
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/card", "POST", json_encode($Data));
    }

    public function CreateWithToken($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "Token"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['token'] = $Params["Token"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/token", "POST", json_encode($Data));
    }

    public function CreateWithCustomer($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "CustomerId"));

        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $Reference);

        $Data['customerId'] = $Params["CustomerId"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/customer", "POST", json_encode($Data));
    }

    public function CreateWithPaymentMethod($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "PaymentMethodId"));

        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $Reference);

        $Data['paymentMethodId'] = $Params["PaymentMethodId"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/payment_method", "POST", json_encode($Data));
    }

    public function Single($ChargeId)
    {
        $url = "/charge/" . urlencode($ChargeId);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Refund($ChargeId, $Amount = NULL)
    {
        $url = "/charge/" . urlencode($ChargeId);

        if (!is_null($Amount))
        {
            $url = $url . "?amount=" . urlencode($Amount);
        }

        return HttpWrapper::CallApi($url, "DELETE", "");
    }

    public function Search($Parameters)
    {
        try
        {
            $url = "/charge" . UrlTools::CreateQueryString($Parameters, $this->ValidSearchKeys);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
    }

    private function BuildCreateChargeJson($Params)
    {
        return [
            'amount'        => $Params["Amount"],
            'currency'      => $Params["Currency"],
            'reference'     => $Params["Reference"]
        ];
    }
}