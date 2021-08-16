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
    private $ValidSearchKeys = array("reference", "email", "addedafter", "addedbefore", "search", "paymentmethodid", "limit", "skip");

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

    public function CreateWithToken($Reference, $FirstName, $LastName, $Email, $Phone, $Token)
    {
        $Data = $this->BuildCreateCustomerJson($Reference, $FirstName, $LastName, $Email, $Phone);

        $Data['providerId'] = $ProviderId;
        $Data['token'] = $Token;
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/token", "POST", json_encode($Data));
    }

    public function Single($CustomerId)
    {
        $url = "/customer/" . urlencode($CustomerId);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Search($Parameters)
    {
        try
        {
            $url = "/customer" . UrlTools::CreateQueryString($Parameters, $this->ValidSearchKeys);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
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