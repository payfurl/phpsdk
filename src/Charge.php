<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");

/*
 * (c) payFurl
 */
class Charge
{
    private $ActionMap = array("charge_card" => "POST");

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

    // TODO: add Search, Single, Refund

    private function BuildCreateChargeJson($Amount, $Currency, $Reference)
    {
        return [
            'amount'        => $Amount,
            'currency'      => $Currency,
            'reference'     => $Reference
        ];
    }
}