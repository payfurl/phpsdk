<?php

namespace payFURL\Sdk;

use payFURL\Sdk\Config;
use payFURL\Sdk\ResponseException;

require_once(__DIR__ . "/../Config.php");
require_once(__DIR__ . "/../ResponseException.php");

/**
 * @copyright PayFURL
 */

class HttpWrapper
{
    static function CallApi($endpoint, $method, $body, $addHeaders = [])
    {
        $url = Config::$BaseUrl . $endpoint;

        if (Config::$EnableDebug) {
            print "calling URL: " . $url . "\n";
            print "Timeout: " . Config::$TimeoutMilliseconds . "\n";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, Config::$TimeoutMilliseconds);

        if (strtolower(Config::$Environment) == "local") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $headers = [
            "Content-Type: application/json",
            "Content-Length: " . strlen($body),
            "x-secretkey:" . Config::$SecretKey,
        ];
        if (count($addHeaders) > 0) {
            foreach ($addHeaders as $key => $value) {
                $headers[] = $key . ':' . $value;
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch);
        curl_close($ch);

        // hande timeout
        if ($info["http_code"] == 0) {
            if (Config::$EnableDebug) {
                var_dump($response);
                var_dump($info);
                var_dump($error);
            }
            throw new ResponseException("Request Timeout", 408);
        }

        // error handling
        if ($info["http_code"] != 200 && $info["http_code"] != 201) {
            $responseJson = json_decode($response, true);
            $message = "";
            if (array_key_exists("message", $responseJson)) {
                $message = $responseJson["message"];
            }
            if (Config::$EnableDebug) {
                var_dump($response);
                var_dump($info);
                var_dump($error);
            }
            throw new ResponseException($message, $info["http_code"]);
        }

        return json_decode($response, true);
    }
}
