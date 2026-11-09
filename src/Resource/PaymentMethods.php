<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Write;
use payFURL\Sdk\Model\NewPaymentMethodBankPayment;
use payFURL\Sdk\Model\NewPaymentMethodCard;
use payFURL\Sdk\Model\NewPaymentMethodToken;
use payFURL\Sdk\Model\NewPaymentMethodVault;
use payFURL\Sdk\Model\NewPayToAgreement;
use payFURL\Sdk\Model\NewProviderToken;
use payFURL\Sdk\Model\PaymentMethodData;
use payFURL\Sdk\Model\PaymentMethodList;
use payFURL\Sdk\Model\PaymentMethodSearch;
use payFURL\Sdk\Model\SingleUseToken;
use payFURL\Sdk\Model\UpdatePaymentMethod;

/**
 * Payment methods without a customer (PaymentMethodController). To save one on a customer, use `customers`.
 */
final class PaymentMethods
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /payment_method/card` */
    public function createWithCard(NewPaymentMethodCard $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/card', $paymentMethod->toArray()));
    }

    /** `POST /payment_method/vault`: a card from another payment method's vaulted card, on another provider. */
    public function createWithVault(NewPaymentMethodVault $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/vault', $paymentMethod->toArray()));
    }

    /** `POST /payment_method/payto` */
    public function createWithPayTo(NewPayToAgreement $agreement): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/payto', $agreement->toArray()));
    }

    /** `POST /payment_method/token` */
    public function createWithToken(NewPaymentMethodToken $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/token', $paymentMethod->toArray()));
    }

    /** `POST /payment_method/provider_single_use_token` */
    public function createWithSingleUseToken(SingleUseToken $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/provider_single_use_token', $paymentMethod->toArray()));
    }

    /** `POST /payment_method/provider_token` */
    public function createWithProviderToken(NewProviderToken $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/provider_token', $paymentMethod->toArray()));
    }

    /** `POST /payment_method/bank_account` */
    public function createWithBankAccount(NewPaymentMethodBankPayment $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->post('/payment_method/bank_account', $paymentMethod->toArray()));
    }

    /** `GET /payment_method/{paymentMethodId}` */
    public function get(string $paymentMethodId): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->get('/payment_method/' . ApiClient::id($paymentMethodId, 'paymentMethodId')));
    }

    /** `GET /payment_method` */
    public function search(PaymentMethodSearch $search = new PaymentMethodSearch()): PaymentMethodList
    {
        return PaymentMethodList::fromArray($this->api->get('/payment_method', $search->toQuery()));
    }

    /** `PUT /payment_method/{paymentMethodId}`: a card's expiry date, cardholder or number, and the metadata. */
    public function update(string $paymentMethodId, UpdatePaymentMethod $paymentMethod): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->put(
            '/payment_method/' . ApiClient::id($paymentMethodId, 'paymentMethodId'),
            $paymentMethod->toArray(),
        ));
    }

    /** `DELETE /payment_method/{paymentMethodId}`, and with `$gatewayDelete` at the gateway too. */
    public function delete(string $paymentMethodId, ?bool $gatewayDelete = null): PaymentMethodData
    {
        return PaymentMethodData::fromArray($this->api->delete(
            '/payment_method/' . ApiClient::id($paymentMethodId, 'paymentMethodId'),
            Write::query(['gatewayDelete' => $gatewayDelete]),
        ));
    }
}
