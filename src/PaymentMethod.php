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
    private $ValidSearchKeys = array("addedafter", "addedbefore", "providerId", "customerId", "paymentType",
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
}