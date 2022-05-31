<?php
namespace payFURL\Sdk;

/*
 * (c) payFURL
 */
class Config
{
    public static $BaseUrl;
    public static $SecretKey;
    public static $TimeoutMilliseconds;
    public static $Environment;
    public static $EnableDebug;

    public static function initialise($SecretKey, $Environment, $TimeoutMilliseconds = 60000, $EnableDebug = false)
    {
        self::$Environment = strtolower($Environment);

        if (self::$Environment == "local") {
            self::$BaseUrl = "https://localhost:5001";
        } else if (self::$Environment == "sandbox") {
            self::$BaseUrl = "https://sandbox-api.payfurl.com";
        } else if (self::$Environment == "prod") {
            self::$BaseUrl = "https://api.payfurl.com";
        }

        self::$SecretKey = $SecretKey;
        self::$TimeoutMilliseconds = $TimeoutMilliseconds;
        self::$EnableDebug = $EnableDebug;
    }
}
