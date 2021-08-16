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

        $result = $svc->CreateWithCard("123", "FirstName", "LastName", "test@test.com", "98761234", $this->CardProviderId, "4111111111111111", "10/30", "123", "Test Cardholder");

        $this->assertIsString($result["customerId"]);
    }

    public function testSingle(): void
    {
        $svc = new Customer();

        $customerResult = $svc->CreateWithCard("123", "FirstName", "LastName", "test@test.com", "98761234", $this->CardProviderId, "4111111111111111", "10/30", "123", "Test Cardholder");

        $singleResult = $svc->Single($customerResult["customerId"]);
        
        $this->assertSame($customerResult["customerId"], $singleResult["customerId"]);
    }

    public function testSearch(): void
    {
        $svc = new Customer();

        $Reference = bin2hex(random_bytes(16));
        $result = $svc->CreateWithCard($Reference, "FirstName", "LastName", "test@test.com", "98761234", $this->CardProviderId, "4111111111111111", "10/30", "123", "Test Cardholder");

        $searchResult = $svc->Search(array("Reference" => $Reference));
        
        $this->assertSame(1, $searchResult["count"]);
    }

    // Commented out while sorting out permissions
    // public function testCreateWithToken(): void
    // {
    //     $svc = new Customer();

    //     $result = $svc->CreateWithCard("123", "FirstName", "LastName", "test@test.com", "98761234", "<token_here>");

    //     $this->assertIsString($result["customerId"]);
    // }
}