<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/PaymentMethod.php');
require_once(__DIR__ . '/../src/Customer.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\Config;
use payFURL\Sdk\PaymentMethod;
use payFURL\Sdk\Customer;
use payFURL\Sdk\ResponseException;

final class PaymentMethodTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testCheckout(): void
    {
        $svc = new PaymentMethod();

        $result = $svc->Checkout(['Amount' => 15.5, 'Currency' => 'AUD', 'ProviderId' => $this->PaypalProviderId]);

        $this->assertIsString($result['checkoutId']);
    }

    /**
     * @throws ResponseException
     */
    public function testSearch(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
                                                           'ProviderId' => $this->CardProviderId,
                                                           'PaymentInformation' => [
                                                               'CardNumber' => '4111111111111111',
                                                               'ExpiryDate' => '10/30',
                                                               'Ccv' => '123',
                                                               'Cardholder' => 'Test Cardholder']]);

        $svc = new PaymentMethod();

        $result = $svc->Search(['customerId' => $customerResult['customerId']]);

        $this->assertEquals(1, $result['count']);
    }

    /**
     * @throws ResponseException
     */
    public function testSingle(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
                                                           'ProviderId' => $this->CardProviderId,
                                                           'PaymentInformation' => [
                                                               'CardNumber' => '4111111111111111',
                                                               'ExpiryDate' => '10/30',
                                                               'Ccv' => '123',
                                                               'Cardholder' => 'Test Cardholder']]);

        $svc = new PaymentMethod();

        $result = $svc->Single(['PaymentMethodId' => $customerResult['defaultPaymentMethod']['paymentMethodId']]);

        $this->assertIsString($result['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testCreatePaymentMethodWithCard(): void
    {
        $paymentMethodSvc = new PaymentMethod();

        $paymentMethodResult = $paymentMethodSvc->CreatePaymentMethodWithCard([
                                                                                  'ProviderId' => $this->CardProviderId,
                                                                                  'PaymentInformation' => [
                                                                                      'CardNumber' => '4111111111111111',
                                                                                      'ExpiryDate' => '10/30',
                                                                                      'Ccv' => '123',
                                                                                      'Cardholder' => 'Test Cardholder']]);

        $this->assertIsString($paymentMethodResult['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testCreatePaymentMethodWithVault(): void
    {
        $paymentMethodSvc = new PaymentMethod();

        $paymentMethodResult = $paymentMethodSvc->CreatePaymentMethodWithCard([
                                                                                  'ProviderId' => $this->CardProviderId,
                                                                                  'PaymentInformation' => [
                                                                                      'CardNumber' => '4111111111111111',
                                                                                      'ExpiryDate' => '10/30',
                                                                                      'Ccv' => '123',
                                                                                      'Cardholder' => 'Test Cardholder'],
                                                                                  'VaultCard' => true]);

        $result = $paymentMethodSvc->CreatePaymentMethodWithVault([
                                                                      'ProviderId' => $this->CardProviderId,
                                                                      'VaultId' => $paymentMethodResult['vaultId'],
                                                                      'PaymentMethodId' => $paymentMethodResult['paymentMethodId']]);

        $this->assertIsString($paymentMethodResult['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testCreatePaymentMethodWithPayTo(): void
    {
        $paymentMethodSvc = new PaymentMethod();

        $paymentMethodResult = $paymentMethodSvc->CreateWithPayTo([
            'ProviderId' => $this->PayToProviderId,
            'PayerName' => 'This is a name',
            'Description' => 'This is a description',
            'MaximumAmount' => 500,
            'PayerPayIdDetails' => [
                'PayId' => 'david_jones@email.com',
                'PayIdType' => 'EMAIL'
            ]]);

        $this->assertIsString($paymentMethodResult['paymentMethodId']);
    }
}
