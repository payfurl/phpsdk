<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Charge.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Charge;
use payFURL\Sdk\ResponseException;

final class ChargeTest extends TestBase
{
    public function testCreateWithCard(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCard(15.5, "AUD", $this->CardProviderId, "123", "4111111111111111", "10/30", "123", "Test Cardholder")
            ->call();

        $this->assertSame('SUCCESS', $result["status"]);
    }

    public function testWithInvalidProvider(): void
    {
        $svc = new Charge();

        $this->CardProviderId = "123";

        $this->expectException(ResponseException::class);
        $result = $svc->CreateWithCard(15.5, "AUD", $this->CardProviderId, "123", "4111111111111111", "10/30", "123", "Test Cardholder")
            ->call();
    }
}