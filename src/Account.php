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
    public function Register($EmailAddress, $Password, $Timezone)
    {
        $Data = [
            'emailAddress' => $EmailAddress,
            'password' => $Password,
            'timezone' => $Timezone
        ];
        
        $Data = ArrayTools::CleanEmpty($Data);

        return HttpWrapper::CallApi("/account/register", "POST", json_encode($Data));
    }
}