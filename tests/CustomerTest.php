<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");
require_once(__DIR__ . "/../src/Customer.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Config;
use payFURL\Sdk\Customer;
use payFURL\Sdk\ResponseException;

final class CustomerTest extends TestBase
{
    public function testCreateWithCard(): void
    {
        $svc = new Customer();

        $result = $svc->CreateWithCard([
            "Reference" => "123",
            "FirstName" => "FirstName",
            "LastName" => "LastName",
            "Email" => "test@test.com",
            "Phone" => "98761234",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $this->assertIsString($result["customerId"]);
    }

    public function testSingle(): void
    {
        $svc = new Customer();

        $customerResult = $svc->CreateWithCard([
            "Reference" => "123",
            "FirstName" => "FirstName",
            "LastName" => "LastName",
            "Email" => "test@test.com",
            "Phone" => "98761234",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $singleResult = $svc->Single($customerResult["customerId"]);
        
        $this->assertSame($customerResult["customerId"], $singleResult["customerId"]);
    }

    public function testSearch(): void
    {
        $svc = new Customer();

        $Reference = bin2hex(random_bytes(16));
        $result = $svc->CreateWithCard([
            "Reference" => $Reference,
            "FirstName" => "FirstName",
            "LastName" => "LastName",
            "Email" => "test@test.com",
            "Phone" => "98761234",
            "ProviderId" => $this->CardProviderId,
            "CardNumber" => "4111111111111111",
            "ExpiryDate" => "10/30",
            "Ccv" => "123",
            "Cardholder" => "Test Cardholder"]);

        $searchResult = $svc->Search(array("Reference" => $Reference));
        
        $this->assertSame(1, $searchResult["count"]);
    }
}