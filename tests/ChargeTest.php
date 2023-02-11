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

        $result = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $this->assertSame('SUCCESS', $result["status"]);
    }

    public function testCreateWithCardWithWebhook(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder",
            "WebhookConfigUrl" => "https://webhook.site/1da8cac9-fef5-47bf-a276-81856f73d7ca",
            "WebhookConfigAuthorization" => "Basic user:password"
        ]);

        $this->assertSame('SUCCESS', $result["status"]);
    }

    public function testCreateWithCardLeastCost(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCardLeastCost([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $this->assertSame('SUCCESS', $result["status"]);
    }

    public function testWithInvalidProvider(): void
    {
        $svc = new Charge();

        $this->expectException(ResponseException::class);

        $result = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => "invalid_provider",
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);
    }

    public function testWithShortTimeout(): void
    {
        $svc = new Charge();
        $this->expectException(ResponseException::class);
        $this->expectExceptionCode(408);

        $Timeout = Config::$TimeoutMilliseconds = 10;

        Config::$TimeoutMilliseconds = $Timeout;

        $result = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);
    }

    public function testSingle(): void
    {
        $svc = new Charge();

        $chargeResult = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $singleResult = $svc->Single(["ChargeId" => $chargeResult["chargeId"]]);
        
        $this->assertSame($chargeResult["chargeId"], $singleResult["chargeId"]);
    }

    public function testRefund(): void
    {
        $svc = new Charge();

        $chargeResult = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => "123",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $refundResult = $svc->Refund(["ChargeId" => $chargeResult["chargeId"], "Amount" => 5]);
        
        $this->assertSame(5, $refundResult["refundedAmount"]);
    }

    public function testSearch(): void
    {
        $svc = new Charge();

        $Reference = bin2hex(random_bytes(16));
        $result = $svc->CreateWithCard([
            "Amount" => 15.5,
            "Currency" => "AUD",
            "Reference" => $Reference,
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $searchResult = $svc->Search(array("Reference" => $Reference));
        
        $this->assertSame(1, $searchResult["count"]);
    }

    public function testInvalidParameters(): void
    {
        $svc = new Charge();
        $this->expectException(ResponseException::class);

        $result = $svc->CreateWithCard(["Amount" => 15.5]);
    }
}