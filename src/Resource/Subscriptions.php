<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\Subscription;
use payFURL\Sdk\Model\SubscriptionCreate;
use payFURL\Sdk\Model\SubscriptionList;
use payFURL\Sdk\Model\SubscriptionSearch;
use payFURL\Sdk\Model\SubscriptionStatus;
use payFURL\Sdk\Model\SubscriptionUpdate;
use payFURL\Sdk\Model\UpdateSubscriptionStatus;

/**
 * Recurring charges on a payment method (SubscriptionController).
 */
final class Subscriptions
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /subscription/payment_method` */
    public function create(SubscriptionCreate $subscription): Subscription
    {
        return Subscription::fromArray($this->api->post('/subscription/payment_method', $subscription->toArray()));
    }

    /** `GET /subscription/{subscriptionId}` */
    public function get(string $subscriptionId): Subscription
    {
        return Subscription::fromArray($this->api->get('/subscription/' . ApiClient::id($subscriptionId, 'subscriptionId')));
    }

    /** `GET /subscription` */
    public function search(SubscriptionSearch $search = new SubscriptionSearch()): SubscriptionList
    {
        return SubscriptionList::fromArray($this->api->get('/subscription', $search->toQuery()));
    }

    /** `PUT /subscription/{subscriptionId}` */
    public function update(string $subscriptionId, SubscriptionUpdate $subscription): Subscription
    {
        return Subscription::fromArray($this->api->put('/subscription/' . ApiClient::id($subscriptionId, 'subscriptionId'), $subscription->toArray()));
    }

    /** `PUT /subscription/{subscriptionId}/status`: pause (`Suspended`), resume (`Active`) or cancel it. */
    public function updateStatus(string $subscriptionId, SubscriptionStatus $status): Subscription
    {
        return Subscription::fromArray($this->api->put(
            '/subscription/' . ApiClient::id($subscriptionId, 'subscriptionId') . '/status',
            (new UpdateSubscriptionStatus($status))->toArray(),
        ));
    }

    /** `DELETE /subscription/{subscriptionId}`: cancels it. */
    public function delete(string $subscriptionId): Subscription
    {
        return Subscription::fromArray($this->api->delete('/subscription/' . ApiClient::id($subscriptionId, 'subscriptionId')));
    }
}
