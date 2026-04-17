<?php

namespace payFURL\Sdk;

use payFURL\Sdk\Config;
use payFURL\Sdk\ErrorCode;
use payFURL\Sdk\ResponseException;

require_once(__DIR__ . "/../Config.php");
require_once(__DIR__ . "/../ErrorCode.php");
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
            print("\nCalling URL: " . $url . "\n");
            print("Timeout: " . Config::$TimeoutMilliseconds . "\n");
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
        if (PHP_VERSION_ID < 80000) {
            curl_close($ch);
        }

        // hande timeout
        if ($info["http_code"] == 0) {
            if (Config::$EnableDebug) {
                print("Response:");
                print_r($response);
                print("Info:");
                print_r($info);
                print("Error:");
                print_r($error);
            }
            throw new ResponseException(
                "Request Timeout",
                ErrorCode::Timeout,
                408,
                true,
                ErrorCode::urlFor(ErrorCode::Timeout),
                "",
                []
            );
        }

        // error handling
        if ($info["http_code"] != 200 && $info["http_code"] != 201) {
            $responseJson = json_decode($response, true);
            if (!is_array($responseJson)) {
                throw new ResponseException(
                    "Unknown Error",
                    ErrorCode::UnknownError,
                    $info["http_code"] ?: 500,
                    false,
                    ErrorCode::urlFor(ErrorCode::UnknownError),
                    "",
                    []
                );
            }

            if (Config::$EnableDebug) {
                print("Request:");
                print_r($body);
                print("Response:");
                print_r($response);
                print("Info:");
                print_r($info);
                print("Error:");
                print_r($error);
            }
            $message = "Unknown Error";
            if (array_key_exists("message", $responseJson)) {
                $message = $responseJson["message"];
            }
            $errorCode = ErrorCode::UnknownError;
            if (array_key_exists("code", $responseJson)) {
                $code = $responseJson["code"];
                if (is_int($code) || (is_string($code) && ctype_digit($code))) {
                    $errorCode = (int) $code;
                }
            }
            $isRetryable = false;
            if (array_key_exists("isRetryable", $responseJson)) {
                $isRetryable = (bool) $responseJson["isRetryable"];
            }
            $type = $responseJson["type"] ?? ErrorCode::urlFor($errorCode);
            $resource = $responseJson["resource"] ?? "";
            $details = is_array($responseJson["details"] ?? null)
                ? $responseJson["details"]
                : null;

            throw new ResponseException($message, $errorCode, $info["http_code"], $isRetryable, $type, $resource, $details);
        }

        return json_decode($response, true);
    }
}
