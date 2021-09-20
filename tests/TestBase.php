<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");

use payFURL\Sdk\Config;

class TestBase extends TestCase
{
    public $CardProviderId = "5deae6c2d560623e10966b91";

    protected function setUp(): void
    {
        Config::initialise("LOCAL", "SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c", 60000, false);
    }
}