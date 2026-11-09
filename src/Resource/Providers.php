<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\NewProvider;
use payFURL\Sdk\Model\Provider;
use payFURL\Sdk\Model\UpdateProvider;

/**
 * The account's gateways (ProviderController).
 */
final class Providers
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /provider` */
    public function create(NewProvider $provider): Provider
    {
        return Provider::fromArray($this->api->post('/provider', $provider->toArray()));
    }

    /** `PUT /provider/{providerId}` */
    public function update(string $providerId, UpdateProvider $provider): Provider
    {
        return Provider::fromArray($this->api->put('/provider/' . ApiClient::id($providerId, 'providerId'), $provider->toArray()));
    }

    /** `DELETE /provider/{providerId}`: archives the provider. */
    public function delete(string $providerId): Provider
    {
        return Provider::fromArray($this->api->delete('/provider/' . ApiClient::id($providerId, 'providerId')));
    }
}
