<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\NewVault;
use payFURL\Sdk\Model\VaultData;
use payFURL\Sdk\Model\VaultDataWithPCI;

/**
 * Card numbers stored by payFURL, to send to another gateway later (VaultController).
 */
final class Vaults
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /vault` */
    public function create(NewVault $vault): VaultData
    {
        return VaultData::fromArray($this->api->post('/vault', $vault->toArray()));
    }

    /** `GET /vault/{vaultId}`: with the full card number and CVV, so handle the result as card data (PCI DSS). */
    public function get(string $vaultId): VaultDataWithPCI
    {
        return VaultDataWithPCI::fromArray($this->api->get('/vault/' . ApiClient::id($vaultId, 'vaultId')));
    }

    /** `DELETE /vault/{vaultId}` */
    public function delete(string $vaultId): VaultData
    {
        return VaultData::fromArray($this->api->delete('/vault/' . ApiClient::id($vaultId, 'vaultId')));
    }
}
