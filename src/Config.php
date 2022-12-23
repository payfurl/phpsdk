<?php
namespace payFURL\Sdk;

/**
 * @copyright PayFURL
 */
class Config
{
    public static string $BaseUrl;
    public static string $SecretKey;
    public static int $TimeoutMilliseconds;
    public static string $Environment;
    public static bool $EnableDebug;

    public static function initialise($SecretKey, $Environment, $TimeoutMilliseconds = 60000, $EnableDebug = false)
    {
        self::$Environment = strtolower($Environment);

        if (self::$Environment == "local") {
            self::$BaseUrl = "https://localhost:5001";
        } else if (self::$Environment == "development") {
            self::$BaseUrl = "https://develop-api.payfurl.com";
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
