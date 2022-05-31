<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFURL
 */
class Transfer
{
    private $ValidSearchKeys = array("reference", "providerId", "status", "addedafter", "addedbefore", "limit", "SortBy", "skip");

    public function Create($Params)
    {       
        ArrayTools::ValidateKeys($Params, array("GroupReference", "ProviderId", "ChargeId"));

        $Data = [
            'groupReference' => $Params["GroupReference"],
            'providerId' => $Params["ProviderId"],
            'chargeId' => $Params["GroupReference"],
            'transfers' => $Params["Transfers"],
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/transfer", "POST", json_encode($Data));
    }

    public function Single($TransferId)
    {
        $url = "/transfer/" . urlencode($TransferId);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Search($Parameters)
    {
        try
        {
            $url = "/transfer" . UrlTools::CreateQueryString($Parameters, $this->ValidSearchKeys);
        }
        catch (Exception $ex)
        {
            throw new ResponseException($ex->message, 0);
        }
         
        return HttpWrapper::CallApi($url, "GET", "");
    }
}