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
            'cardholder' => $Params["Cardholder"] ?? null
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/card", "POST", json_encode($Data));
    }

    public function CreateWithCardLeastCost($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "CardNumber", "ExpiryDate", "Ccv"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['paymentInformation'] = [
            'cardNumber' => $Params["CardNumber"],
            'expiryDate' => $Params["ExpiryDate"],
            'ccv' => $Params["Ccv"],
            'cardholder' => $Params["Cardholder"] ?? null
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/card/least_cost", "POST", json_encode($Data));
    }

    public function CreateWithToken($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Token"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['token'] = $Params["Token"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/token", "POST", json_encode($Data));
    }

    public function CreateWithCustomer($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "CustomerId"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['customerId'] = $Params["CustomerId"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/customer", "POST", json_encode($Data));
    }

    public function CreateWithPaymentMethod($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Amount", "ProviderId", "PaymentMethodId"));

        $Data = $this->BuildCreateChargeJson($Params);

        $Data['paymentMethodId'] = $Params["PaymentMethodId"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/payment_method", "POST", json_encode($Data));
    }

    public function Single($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ChargeId"));

        $url = "/charge/" . urlencode($Params["ChargeId"]);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Refund($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ChargeId"));

        $url = "/charge/" . urlencode($Params["ChargeId"]);

        if (!is_null($Params["Amount"]))
        {
            $url = $url . "?amount=" . urlencode($Params["Amount"]);
        }

        return HttpWrapper::CallApi($url, "DELETE", "");
    }

    public function Search($Params)
    {
        try
        {
            $url = "/charge" . UrlTools::CreateQueryString($Params, $this->ValidSearchKeys);
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
            'reference'     => $Params["Reference"] ?? null
        ];
    }
}