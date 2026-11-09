<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;
use payFURL\Sdk\Model\CustomerData;
use payFURL\Sdk\Model\CustomerList;
use payFURL\Sdk\Model\CustomerSearch;
use payFURL\Sdk\Model\NewCustomerBankPayment;
use payFURL\Sdk\Model\NewCustomerCard;
use payFURL\Sdk\Model\NewCustomerPayTo;
use payFURL\Sdk\Model\NewCustomerProviderToken;
use payFURL\Sdk\Model\NewCustomerToken;
use payFURL\Sdk\Model\NewPaymentMethodBankPayment;
use payFURL\Sdk\Model\NewPaymentMethodCard;
use payFURL\Sdk\Model\NewPaymentMethodToken;
use payFURL\Sdk\Model\NewPayToAgreement;
use payFURL\Sdk\Model\PaymentMethodData;
use payFURL\Sdk\Model\SingleUseToken;
use payFURL\Sdk\Model\UpdateCustomer;

/**
 * Customers and their payment methods (CustomerController).
 */
final class Customers
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /customer/card` */
    public function createWithCard(NewCustomerCard $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/card', $customer->toArray()));
    }

    /** `POST /customer/token` */
    public function createWithToken(NewCustomerToken $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/token', $customer->toArray()));
    }

    /** `POST /customer/payto` */
    public function createWithPayTo(NewCustomerPayTo $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/payto', $customer->toArray()));
    }

    /** `POST /customer/provider_token`: a payment method the gateway already stores. */
    public function createWithProviderToken(NewCustomerProviderToken $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/provider_token', $customer->toArray()));
    }

    /** `POST /customer/provider_single_use_token` */
    public function createWithSingleUseToken(SingleUseToken $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/provider_single_use_token', $customer->toArray()));
    }

    /** `POST /customer/bank_account` */
    public function createWithBankAccount(NewCustomerBankPayment $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->post('/customer/bank_account', $customer->toArray()));
    }

    /** `GET /customer/{customerId}` */
    public function get(string $customerId): CustomerData
    {
        return CustomerData::fromArray($this->api->get('/customer/' . ApiClient::id($customerId, 'customerId')));
    }

    /** `GET /customer` */
    public function search(CustomerSearch $search = new CustomerSearch()): CustomerList
    {
        return CustomerList::fromArray($this->api->get('/customer', $search->toQuery()));
    }

    /** `PUT /customer/{customerId}` */
    public function update(string $customerId, UpdateCustomer $customer): CustomerData
    {
        return CustomerData::fromArray($this->api->put('/customer/' . ApiClient::id($customerId, 'customerId'), $customer->toArray()));
    }

    /**
     * `DELETE /customer/{customerId}`: removes the customer and its payment methods, and with `$gatewayDelete` also
     * deletes them at the gateways.
     */
    public function delete(string $customerId, ?bool $gatewayDelete = null): CustomerData
    {
        return CustomerData::fromArray($this->api->delete(
            '/customer/' . ApiClient::id($customerId, 'customerId'),
            Write::query(['gatewayDelete' => $gatewayDelete]),
        ));
    }

    /**
     * `GET /customer/{customerId}/payment_method`
     *
     * @return list<PaymentMethodData>
     */
    public function paymentMethods(string $customerId): array
    {
        return Read::list(
            $this->api->get('/customer/' . ApiClient::id($customerId, 'customerId') . '/payment_method'),
            PaymentMethodData::class,
        );
    }

    /** `POST /customer/{customerId}/payment_method/card` */
    public function addPaymentMethodWithCard(string $customerId, NewPaymentMethodCard $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post(
            '/customer/' . ApiClient::id($customerId, 'customerId') . '/payment_method/card',
            $paymentMethod->toArray(),
        ));
    }

    /** `POST /customer/{customerId}/payment_method/token` */
    public function addPaymentMethodWithToken(string $customerId, NewPaymentMethodToken $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post(
            '/customer/' . ApiClient::id($customerId, 'customerId') . '/payment_method/token',
            $paymentMethod->toArray(),
        ));
    }

    /** `POST /customer/{customerId}/payment_method/payto` */
    public function addPaymentMethodWithPayTo(string $customerId, NewPayToAgreement $agreement): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post(
            '/customer/' . ApiClient::id($customerId, 'customerId') . '/payment_method/payto',
            $agreement->toArray(),
        ));
    }

    /** `POST /customer/{customerId}/bank_account` */
    public function addPaymentMethodWithBankAccount(string $customerId, NewPaymentMethodBankPayment $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post(
            '/customer/' . ApiClient::id($customerId, 'customerId') . '/bank_account',
            $paymentMethod->toArray(),
        ));
    }
}
