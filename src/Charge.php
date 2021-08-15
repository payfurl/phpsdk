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

    public function CreateWithCard($Amount, $Currency, $ProviderId, $Reference, $CardNumber, $ExpiryDate, $Ccv, $Cardholder)
    {
        $Data = $this->BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference);

        $Data['paymentInformation'] = [
            'cardNumber' => $CardNumber,
            'expiryDate' => $ExpiryDate,
            'ccv' => $Ccv,
            'cardholder' => $Cardholder
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/charge/card", "POST", json_encode($Data));
    }

    // TODO: add CreateWithToken, CreateWithCustomer, CreateWithPaymentMethod, Search, Single, Refund

    private function BuildCreateChargeJson($Amount, $Currency, $ProviderId, $Reference)
    {
        return [
            'amount'        => $Amount,
            'currency'      => $Currency,
            'providerId'    => $ProviderId,
            'reference'     => $Reference
        ];
    }
}