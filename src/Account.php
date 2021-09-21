<?php
namespace payFURL\Sdk;

require_once(__DIR__ . "/tools/HttpWrapper.php");
require_once(__DIR__ . "/tools/ArrayTools.php");
require_once(__DIR__ . "/tools/UrlTools.php");

/*
 * (c) payFurl
 */
class Account
{
    public function Register($Params)
    {
        ArrayTools::ValidateKeys($Params, array("EmailAddress", "Password"));

        $Data = [
            'emailAddress' => $Params["EmailAddress"],
            'password' => $Params["Password"],
            'timezone' => $Params["Timezone"]
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/account/register", "POST", json_encode($Data));
    }
}