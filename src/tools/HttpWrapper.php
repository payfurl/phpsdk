<?php
namespace payFURL\Sdk;

use payFURL\Sdk\Config;
use payFURL\Sdk\ResponseException;

require_once(__DIR__ . "/../Config.php");
require_once(__DIR__ . "/../ResponseException.php");

/*
 * (c) payFURL
 */
class HttpWrapper
{
    static function CallApi($Endpoint, $Method, $Body)
    {
        $Url = Config::$BaseUrl . $Endpoint;

        if (Config::$EnableDebug)
        {
            print "calling URL: " . $Url . "\n";
            print "Timeout: " . Config::$TimeoutMilliseconds . "\n";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Body);
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, Config::$TimeoutMilliseconds);

        if (strtolower(Config::$Environment) == "local")
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

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

        // hande timeout
        if ($Info["http_code"] == 0)
        {
            if (Config::$EnableDebug)
            {
                var_dump($Response);
                var_dump($Info);
                var_dump($Error);
            }
            throw new ResponseException("Request Timeout", 408);
        }

        // error handling
        if ($Info["http_code"] != 200 && $Info["http_code"] != 201)
        {
            $ResponseJson = json_decode($Response, true);
            $Message = "";
            if (array_key_exists("message", $ResponseJson))
            {
                $Message = $ResponseJson["message"];
            }
            if (Config::$EnableDebug)
            {
                var_dump($Response);
                var_dump($Info);
                var_dump($Error);
            }
            throw new ResponseException($Message, $Info["http_code"]);
        }

        return json_decode($Response, true);
    }
}