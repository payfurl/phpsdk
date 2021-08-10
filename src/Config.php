<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
class Config
{
    public static $BaseUrl;
    public static $SecretKey;
    public static $TimeoutMilliseconds;
    public static $Environment;

    public static function initialise($Environment, $SecretKey, $TimeoutMilliseconds = 60000)
    {
        self::$Environment = strtolower($environmentOrUrl);

        if (self::$Environment == "local") {
            self::$url = "https://localhost:5001";
        } else if (self::$Environment == "sandbox") {
            self::$url = "https://sandbox-api.payfurl.com";
        } else if (self::$Environment == "prod") {
            self::$url = "https://api.payfurl.com";
        }

        self::$SecretKey = $SecretKey;
        self::$TimeoutMilliseconds = $TimeoutMilliseconds;
    }
}
