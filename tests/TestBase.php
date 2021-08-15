<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");

use payFURL\Sdk\Config;

class TestBase extends TestCase
{
    public $CardProviderId = "";

    protected function setUp(): void
    {
        Config::initialise("SANDBOX", "", 60000);
    }
}