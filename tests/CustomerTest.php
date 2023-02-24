<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Customer.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\Config;
use payFURL\Sdk\Customer;
use payFURL\Sdk\ResponseException;

final class CustomerTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testCreateWithCard(): void
    {
        $svc = new Customer();

        $result = $svc->CreateWithCard([
                                           'Reference' => '123',
                                           'FirstName' => 'FirstName',
                                           'LastName' => 'LastName',
                                           'Email' => 'test@test.com',
                                           'ProviderId' => $this->CardProviderId,
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder']]);

        $this->assertIsString($result['customerId']);
    }

    /**
     * @throws ResponseException
     */
    public function testSingle(): void
    {
        $svc = new Customer();

        $customerResult = $svc->CreateWithCard([
                                                   'Reference' => '123',
                                                   'FirstName' => 'FirstName',
                                                   'LastName' => 'LastName',
                                                   'Email' => 'test@test.com',
                                                   'ProviderId' => $this->CardProviderId,
                                                   'PaymentInformation' => [
                                                       'CardNumber' => '4111111111111111',
                                                       'ExpiryDate' => '10/30',
                                                       'Ccv' => '123',
                                                       'Cardholder' => 'Test Cardholder']]);

        $singleResult = $svc->Single(['CustomerId' => $customerResult['customerId']]);

        $this->assertSame($customerResult['customerId'], $singleResult['customerId']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testSearch(): void
    {
        $svc = new Customer();

        $Reference = bin2hex(random_bytes(16));
        $result = $svc->CreateWithCard([
                                           'Reference' => $Reference,
                                           'FirstName' => 'FirstName',
                                           'LastName' => 'LastName',
                                           'Email' => 'test@test.com',
                                           'ProviderId' => $this->CardProviderId,
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder']]);

        $searchResult = $svc->Search(array('Reference' => $Reference));

        $this->assertSame(1, $searchResult['count']);
    }

    /**
     * @throws ResponseException
     */
    public function testCustomerPaymentMethods(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
                                                           'Reference' => '123',
                                                           'FirstName' => 'FirstName',
                                                           'LastName' => 'LastName',
                                                           'Email' => 'test@test.com',
                                                           'ProviderId' => $this->CardProviderId,
                                                           'PaymentInformation' => [
                                                               'CardNumber' => '4111111111111111',
                                                               'ExpiryDate' => '10/30',
                                                               'Ccv' => '123',
                                                               'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CustomerPaymentMethods(['CustomerId' => $customerResult['customerId']]);

        $this->assertEquals($result[0]['customerId'], $customerResult['customerId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithCard(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
                                                           'Reference' => '123',
                                                           'FirstName' => 'FirstName',
                                                           'LastName' => 'LastName',
                                                           'Email' => 'test@test.com',
                                                           'ProviderId' => $this->CardProviderId,
                                                           'PaymentInformation' => [
                                                               'CardNumber' => '4111111111111111',
                                                               'ExpiryDate' => '10/30',
                                                               'Ccv' => '123',
                                                               'Cardholder' => 'Test Cardholder']]);

        $customerSvc = new Customer();

        $result = $customerSvc->CreatePaymentMethodWithCard([
                                                                'CustomerId' => $customerResult['customerId'],
                                                                'ProviderId' => $this->CardProviderId,
                                                                'PaymentInformation' => [
                                                                    'CardNumber' => '4111111111111111',
                                                                    'ExpiryDate' => '10/30',
                                                                    'Ccv' => '123'
                                                                ]]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithCard_SetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithCard([
            'CustomerId' => $customerResult['customerId'],
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123'
            ],
            'SetDefault' => true]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithCard_NoSetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithCard([
            'CustomerId' => $customerResult['customerId'],
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123'
            ],
            'SetDefault' => false]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertNotEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
        $this->assertEquals($customerResult['defaultPaymentMethod']['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithToken_SetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithToken([
            'CustomerId' => $customerResult['customerId'],
            'Token' => $this->TokenId,
            'SetDefault' => true]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithToken_NoSetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithToken([
            'CustomerId' => $customerResult['customerId'],
            'Token' => $this->TokenId,
            'SetDefault' => false]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertNotEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
        $this->assertEquals($customerResult['defaultPaymentMethod']['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithPayTo_SetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithPayTo([
            'CustomerId' => $customerResult['customerId'],
            'ProviderId' => $this->CardProviderId,
            'PayerName' => 'This is a name',
            'Description' => 'This is a description',
            'MaximumAmount' => 500,
            'PayerPayIdDetails' => [
                'PayId' => 'david_jones@email.com',
                'PayIdType' => 'EMAIL'
            ],
            'SetDefault' => true]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testAddCustomerPaymentMethodWithPayTo_NoSetDefault(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $result = $customerSvc->CreatePaymentMethodWithPayTo([
            'CustomerId' => $customerResult['customerId'],
            'ProviderId' => $this->CardProviderId,
            'PayerName' => 'This is a name',
            'Description' => 'This is a description',
            'MaximumAmount' => 500,
            'PayerPayIdDetails' => [
                'PayId' => 'david_jones@email.com',
                'PayIdType' => 'EMAIL'
            ],
            'SetDefault' => false]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);
        $this->assertNotEquals($result['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
        $this->assertEquals($customerResult['defaultPaymentMethod']['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }

    /**
     * @throws ResponseException
     */
    public function testUpdateCustomerDefaultPaymentMethodId(): void
    {
        $customerSvc = new Customer();

        $customerResult = $customerSvc->CreateWithCard([
            'Reference' => '123',
            'FirstName' => 'FirstName',
            'LastName' => 'LastName',
            'Email' => 'test@test.com',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123',
                'Cardholder' => 'Test Cardholder']]);

        $paymentMethod = $customerSvc->CreatePaymentMethodWithCard([
            'CustomerId' => $customerResult['customerId'],
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => '123'
            ],
            'SetDefault' => false]);

        $result = $customerSvc->UpdateCustomer([
            'CustomerId' => $customerResult['customerId'],
            'Email' => $customerResult['email'],
            'DefaultPaymentMethodId' => $paymentMethod['paymentMethodId']]);

        $this->assertEquals($result['customerId'], $customerResult['customerId']);

        $singleResult = $customerSvc->Single(['CustomerId' => $customerResult['customerId']]);

        $this->assertNotEquals($paymentMethod['paymentMethodId'], $customerResult['defaultPaymentMethod']['paymentMethodId']);
        $this->assertEquals($paymentMethod['paymentMethodId'], $singleResult['defaultPaymentMethod']['paymentMethodId']);
    }
}
