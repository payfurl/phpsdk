<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");

use payFURL\Sdk\Config;

class TestBase extends TestCase
{
    public $CardProviderId = "a26c371f-94f6-40da-add2-28ec8e9da8ed";
    public $PaypalProviderId = "1cf5deda-28cc-4214-adb5-1e597a37228c";

    protected function setUp(): void
    {
        Config::initialise("SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c", "LOCAL", 60000, false);
    }
}