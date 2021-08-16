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
}