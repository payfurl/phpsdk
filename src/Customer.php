<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFurl
 */
class Customer
{
    private $ValidSearchKeys = array();

    public function CreateWithCard($Reference, $FirstName, $LastName, $Email, $Phone, $ProviderId, $CardNumber, $ExpiryDate, $Ccv, $Cardholder)
    {
        $Data = $this->BuildCreateCustomerJson($Reference, $FirstName, $LastName, $Email, $Phone);

        $Data['providerId'] = $ProviderId;
        $Data['paymentInformation'] = [
            'cardNumber' => $CardNumber,
            'expiryDate' => $ExpiryDate,
            'ccv' => $Ccv,
            'cardholder' => $Cardholder
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/card", "POST", json_encode($Data));
    }

    private function BuildCreateCustomerJson($Reference, $FirstName, $LastName, $Email, $Phone)
    {
        return [
            'reference'     => $Reference,
            'firstName'     => $FirstName,
            'lastName'      => $LastName,
            'email'         => $Email,
            'phone'         => $Phone,
        ];
    }
}