<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");
require_once(__DIR__ . "/../src/Charge.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Config;
use payFURL\Sdk\Charge;
use payFURL\Sdk\ResponseException;

final class ChargeTest extends TestBase
{
    public function testCreateWithCard(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCard(15.5, "AUD", $this->CardProviderId, "123", "4111111111111111", "10/30", "123", "Test Cardholder");

        $this->assertSame('SUCCESS', $result["status"]);
    }

    public function testWithInvalidProvider(): void
    {
        $svc = new Charge();

        $this->expectException(ResponseException::class);
        $result = $svc->CreateWithCard(15.5, "AUD", "invalid_provider", "123", "4111111111111111", "10/30", "123", "Test Cardholder");
    }

    public function testWithShortTimeout(): void
    {
        $svc = new Charge();
        $this->expectException(ResponseException::class);
        $this->expectExceptionCode(408);

        $Timeout = Config::$TimeoutMilliseconds = 10;

        $result = $svc->CreateWithCard(15.5, "AUD", $this->CardProviderId, "123", "4111111111111111", "10/30", "123", "Test Cardholder");

        Config::$TimeoutMilliseconds = $Timeout;
    }
}