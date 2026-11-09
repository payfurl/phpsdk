<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Write;
use payFURL\Sdk\Model\ChargeData;
use payFURL\Sdk\Model\ChargeList;
use payFURL\Sdk\Model\ChargeSearch;
use payFURL\Sdk\Model\NewChargeBankPayment;
use payFURL\Sdk\Model\NewChargeCard;
use payFURL\Sdk\Model\NewChargeCardLeastCost;
use payFURL\Sdk\Model\NewChargeCustomer;
use payFURL\Sdk\Model\NewChargeNetworkToken;
use payFURL\Sdk\Model\NewChargePaymentMethod;
use payFURL\Sdk\Model\NewChargeToken;

/**
 * Charges (ChargeController). A charge is never retried by the SDK: after a `NetworkException`, search by its
 * reference before charging again.
 */
final class Charges
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /charge/card`: a card, on the provider named. */
    public function createWithCard(NewChargeCard $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/card', $charge->toArray()));
    }

    /** `POST /charge/card/least_cost`: a card, on the account's cheapest provider for it. */
    public function createWithCardLeastCost(NewChargeCardLeastCost $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/card/least_cost', $charge->toArray()));
    }

    /** `POST /charge/customer`: a customer's default payment method. */
    public function createWithCustomer(NewChargeCustomer $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/customer', $charge->toArray()));
    }

    /** `POST /charge/payment_method`: a saved payment method. */
    public function createWithPaymentMethod(NewChargePaymentMethod $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/payment_method', $charge->toArray()));
    }

    /** `POST /charge/token`: a token from the browser SDK or a hosted page. */
    public function createWithToken(NewChargeToken $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/token', $charge->toArray()));
    }

    /** `POST /charge/bank_account`: a direct debit from a bank account. */
    public function createWithBankAccount(NewChargeBankPayment $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/bank_account', $charge->toArray()));
    }

    /** `POST /charge/network_token`: a network token. */
    public function createWithNetworkToken(NewChargeNetworkToken $charge): ChargeData
    {
        return ChargeData::fromArray($this->api->post('/charge/network_token', $charge->toArray()));
    }

    /** `GET /charge/{chargeId}` */
    public function get(string $chargeId): ChargeData
    {
        return ChargeData::fromArray($this->api->get('/charge/' . ApiClient::id($chargeId, 'chargeId')));
    }

    /** `GET /charge` */
    public function search(ChargeSearch $search = new ChargeSearch()): ChargeList
    {
        return ChargeList::fromArray($this->api->get('/charge', $search->toQuery()));
    }

    /**
     * `DELETE /charge/{chargeId}`: refunds the charge, all of it when `$amount` is null.
     */
    public function refund(string $chargeId, ?float $amount = null, ?string $comment = null): ChargeData
    {
        return ChargeData::fromArray($this->api->delete(
            '/charge/' . ApiClient::id($chargeId, 'chargeId'),
            Write::query(['amount' => $amount, 'comment' => $comment]),
        ));
    }

    /**
     * `POST /charge/{chargeId}/capture`: captures an authorised charge (`capture: false`), all of it when `$amount`
     * is null.
     */
    public function capture(string $chargeId, ?float $amount = null): ChargeData
    {
        return ChargeData::fromArray($this->api->post(
            '/charge/' . ApiClient::id($chargeId, 'chargeId') . '/capture',
            Write::clean(['amount' => $amount]),
        ));
    }

    /** `DELETE /charge/{chargeId}/void`: cancels an authorised charge. */
    public function void(string $chargeId): ChargeData
    {
        return ChargeData::fromArray($this->api->delete('/charge/' . ApiClient::id($chargeId, 'chargeId') . '/void'));
    }
}
