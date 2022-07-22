<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFURL
 */
class Vault
{
    public function Create($Params)
    {       
        ArrayTools::ValidateKeys($Params, array("CardNumber"));

        $Data = [
            'CardNumber' => $Params["CardNumber"],
            'Ccv' => $Params["Ccv"] ?? null,
            'ExpireDate' => $Params["ExpireDate"] ?? null,
            'ExpireSeconds' => $Params["ExpireSeconds"] ?? null,
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/vault", "POST", json_encode($Data));
    }

    public function Single($VaultId)
    {
        $url = "/vault/" . urlencode($VaultId);

        return HttpWrapper::CallApi($url, "GET", "");
    }

    public function Delete($VaultId)
    {
        $url = "/vault/" . urlencode($VaultId);

        return HttpWrapper::CallApi($url, "DELETE", "");
    }

}