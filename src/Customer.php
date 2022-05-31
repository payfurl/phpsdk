<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFURL
 */
class Customer
{
    private $ValidSearchKeys = array("reference", "email", "addedafter", "addedbefore", "search", "limit", "skip");

    public function CreateWithCard($Params)
    {
        ArrayTools::ValidateKeys($Params, array("ProviderId", "CardNumber", "ExpiryDate", "Ccv"));

        $Data = $this->BuildCreateCustomerJson($Params);

        $Data['providerId'] = $Params["ProviderId"];
        $Data['paymentInformation'] = [
            'cardNumber' => $Params["CardNumber"],
            'expiryDate' => $Params["ExpiryDate"],
            'ccv' => $Params["Ccv"],
            'cardholder' => $Params["Cardholder"] ?? null
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/card", "POST", json_encode($Data));
    }

    public function CreateWithToken($Params)
    {   
        ArrayTools::ValidateKeys($Params, array("Token"));

        $Data = $this->BuildCreateCustomerJson($Params);

        $Data['token'] = $Params["Token"];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/token", "POST", json_encode($Data));
    }

    public function CreatePaymentMethodWithCard($Params)
    {
        ArrayTools::ValidateKeys($Params, array("CustomerId", "ProviderId", "CardNumber", "ExpiryDate", "Ccv"));

        $Data = [
            'providerId' => $Params["ProviderId"],
            'paymentInformation' => [
                'cardNumber' => $Params["CardNumber"],
                'expiryDate' => $Params["ExpiryDate"],
                'ccv' => $Params["Ccv"],
                'cardholder' => $Params["Cardholder"] ?? null
            ]
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/" . urlencode($Params["CustomerId"]) . "/payment_method/card", "POST", json_encode($Data));
    }

    public function CreatePaymentMethodWithToken($Params)
    {
        ArrayTools::ValidateKeys($Params, array("Token"));

        $Data = $this->BuildCreateCustomerJson($Params);

        $Data = [
            'token' => $Params["Token"]
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/customer/" . urlencode($Params["CustomerId"]) . "/payment_method/token", "POST", json_encode($Data));
    }

    public function Single($Params)
    {
        ArrayTools::ValidateKeys($Params, array("CustomerId"));

        $url = "/customer/" . urlencode($Params["CustomerId"]);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Search($Params)
    {
        try
        {
            $url = "/customer" . UrlTools::CreateQueryString($Params, $this->ValidSearchKeys);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function CustomerPaymentMethods($Params)
    {
        $url = "/customer/" . urlencode($Params["customerId"]) . "/payment_method";

        return HttpWrapper::CallApi($url, "GET", "");
    }

    private function BuildCreateCustomerJson($Params)
    {
        return [
            'reference'     => $Params["Reference"] ?? null,
            'firstName'     => $Params["FirstName"] ?? null,
            'lastName'      => $Params["LastName"] ?? null,
            'email'         => $Params["Email"] ?? null,
            'phone'         => $Params["Phone"] ?? null,
        ];
    }
}