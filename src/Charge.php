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
    private $ActionMap = array("charge_card" => "POST");
    private $ValidSearchKeys = array("Reference", "ProviderId", "AmountGreaterThan", "AmountLessThan",
        "CustomerId", "Status", "AddedAfter", "AddedBefore", "SortBy", "Limit", "Skip");

    public function CreateWithCard($Amount, $Currency, $Reference, $ProviderId, $CardNumber, $ExpiryDate, $Ccv, $Cardholder)
    {
        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference);

        $Data['providerId'] = $ProviderId;
        $Data['paymentInformation'] = [
            'cardNumber' => $CardNumber,
            'expiryDate' => $ExpiryDate,
            'ccv' => $Ccv,
            'cardholder' => $Cardholder
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/card", "POST", json_encode($Data));
    }

    public function CreateWithToken($Amount, $Currency, $ProviderId, $Reference, $Token)
    {
        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference);

        $Data['token'] = $Token;
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/token", "POST", json_encode($Data));
    }

    public function CreateWithCustomer($Amount, $Currency, $ProviderId, $Reference, $CustomerId)
    {
        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference);

        $Data['customerId'] = $CustomerId;
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/customer", "POST", json_encode($Data));
    }

    public function CreateWithPaymentMethod($Amount, $Currency, $ProviderId, $Reference, $PaymentMethodId)
    {
        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference);

        $Data['paymentMethodId'] = $PaymentMethodId;
        
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
        // check keys are valid
        foreach ($Parameters as $Key => $Value)
        {
            if (!array_key_exists($Key, $this->ValidSearchKeys))
            {
                throw new ResponseException("Invalid Parameter: " . $Key, 0);
            }
        }

        $url = "/charge" . UrlTools::CreateQueryString($Parameters);
         
        return HttpWrapper::CallApi($url, "GET", "");
    }

    // TODO: add Search

    private function BuildCreateChargeJson($Amount, $Currency, $Reference)
    {
        return [
            'amount'        => $Amount,
            'currency'      => $Currency,
            'reference'     => $Reference
        ];
    }
}