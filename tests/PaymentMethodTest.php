<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");
require_once(__DIR__ . "/../src/PaymentMethod.php");
require_once(__DIR__ . "/../src/Customer.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Config;
use payFURL\Sdk\PaymentMethod;
use payFURL\Sdk\Customer;
use payFURL\Sdk\ResponseException;

final class PaymentMethodTest extends TestBase
{
    public function testCheckout(): void
    {
        $svc = new PaymentMethod();

        $result = $svc->Checkout(["Amount" => 15.5, "Currency" => "AUD", "ProviderId" => $this->PaypalProviderId]);

        $this->assertIsString($result["checkoutId"]);
    }

    public function testCustomerPaymentMethods(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
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

        $paymentMethodSvc = new PaymentMethod();

        $result = $paymentMethodSvc->CustomerPaymentMethods($customerResult["customerId"]);

        $this->assertSame($result[0]["customerId"], $customerResult["customerId"]);
    }
}