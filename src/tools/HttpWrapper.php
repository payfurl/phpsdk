<?php
namespace payFURL\Sdk;

use payFURL\Sdk\Config;
use payFURL\Sdk\ResponseException;

require_once(__DIR__ . "/../Config.php");
require_once(__DIR__ . "/../ResponseException.php");

/*
 * (c) payFurl
 */
class HttpWrapper
{
    static function CallApi($Endpoint, $Method, $Body)
    {
        $Url = Config::$BaseUrl . $Endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Body);
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, Config::$TimeoutMilliseconds);

        $Headers = [
            "Content-Type: application/json",
            "Content-Length: " . strlen($Body),
            "x-secretkey:" . Config::$SecretKey
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);

        $Response = curl_exec($ch);
        $Info = curl_getinfo($ch);
        $Error = curl_errno($ch);
        curl_close($ch);

        // error handling
        if ($Info["http_code"] != 200)
        {
            $ResponseJson = json_decode($Response, true);
            var_dump($ResponseJson);
            throw new ResponseException($ResponseJson["message"], $Info["http_code"]);
        }

        return json_decode($Response, true);
    }
}