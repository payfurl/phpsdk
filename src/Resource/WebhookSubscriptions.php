<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\NewWebhookSubscription;
use payFURL\Sdk\Model\WebhookSubscription;
use payFURL\Sdk\Model\WebhookSubscriptionList;
use payFURL\Sdk\Model\WebhookSubscriptionSearch;

/**
 * Where the account's webhooks go (WebhookSubscriptionController). `Webhook::parse()` checks a webhook's signature.
 */
final class WebhookSubscriptions
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /webhook/subscription` */
    public function create(NewWebhookSubscription $subscription): WebhookSubscription
    {
        return WebhookSubscription::fromArray($this->api->post('/webhook/subscription', $subscription->toArray()));
    }

    /** `GET /webhook/subscription/{webhookSubscriptionId}` */
    public function get(string $webhookSubscriptionId): WebhookSubscription
    {
        return WebhookSubscription::fromArray($this->api->get('/webhook/subscription/' . ApiClient::id($webhookSubscriptionId, 'webhookSubscriptionId')));
    }

    /** `GET /webhook/subscription` */
    public function search(WebhookSubscriptionSearch $search = new WebhookSubscriptionSearch()): WebhookSubscriptionList
    {
        return WebhookSubscriptionList::fromArray($this->api->get('/webhook/subscription', $search->toQuery()));
    }

    /** `DELETE /webhook/subscription/{webhookSubscriptionId}` */
    public function delete(string $webhookSubscriptionId): WebhookSubscription
    {
        return WebhookSubscription::fromArray($this->api->delete('/webhook/subscription/' . ApiClient::id($webhookSubscriptionId, 'webhookSubscriptionId')));
    }
}
