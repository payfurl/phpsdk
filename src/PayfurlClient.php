<?php

declare(strict_types=1);

namespace payFURL\Sdk;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\ApplicationVersion;
use payFURL\Sdk\Model\RootStatus;
use payFURL\Sdk\Resource\Batches;
use payFURL\Sdk\Resource\Charges;
use payFURL\Sdk\Resource\Customers;
use payFURL\Sdk\Resource\Disputes;
use payFURL\Sdk\Resource\Info;
use payFURL\Sdk\Resource\PaymentLinks;
use payFURL\Sdk\Resource\PaymentMethods;
use payFURL\Sdk\Resource\Providers;
use payFURL\Sdk\Resource\Settlements;
use payFURL\Sdk\Resource\Subscriptions;
use payFURL\Sdk\Resource\Tokens;
use payFURL\Sdk\Resource\Transfers;
use payFURL\Sdk\Resource\Vaults;
use payFURL\Sdk\Resource\WebhookSubscriptions;

/**
 * The payFURL API with one account's key:
 *
 *     $payfurl = new PayfurlClient('sectest...-au', Environment::Sandbox);
 *     $charge = $payfurl->charges()->createWithToken(new NewChargeToken(amount: 20.0, token: $token, currency: 'AUD'));
 *
 * A secret key (`sec...`) calls every endpoint. A public key (`pub...`) calls only `info()->providers()`. One client
 * per key: a store with several accounts makes one for each.
 */
final class PayfurlClient
{
    /** This SDK's version: the release tag without its `v`, and in the User-Agent of every request. */
    public const VERSION = '3.0.0';

    private readonly ApiClient $api;

    /**
     * @param string $key the account's secret key, or its public key for `info()->providers()` only
     * @param Environment|string $environment an `Environment`, or its value (`sandbox`, `production`, ...)
     *
     * @throws \InvalidArgumentException for an empty key or an unknown environment
     */
    public function __construct(string $key, Environment|string $environment, ClientOptions $options = new ClientOptions())
    {
        if (trim($key) === '') {
            throw new \InvalidArgumentException('The payFURL key is empty');
        }
        if (is_string($environment)) {
            $environment = Environment::tryFrom(strtolower(trim($environment)))
                ?? throw new \InvalidArgumentException('Unknown payFURL environment "' . $environment . '": use '
                    . implode(', ', array_map(static fn(Environment $e): string => $e->value, Environment::cases())));
        }

        // A key ends in its account's region, such as sectest...-au (AuthService.GenerateKey).
        $dash = strrpos($key, '-');
        $region = $dash === false ? null : strtolower(substr($key, $dash + 1));
        $baseUrl = rtrim($options->baseUrl ?? $environment->baseUrl($region), '/');
        if ($baseUrl === '') {
            throw new \InvalidArgumentException('ClientOptions::$baseUrl is empty');
        }

        $this->api = new ApiClient($key, $baseUrl, $options, verifyTls: $environment !== Environment::Local);
    }

    public function batches(): Batches
    {
        return new Batches($this->api);
    }

    public function charges(): Charges
    {
        return new Charges($this->api);
    }

    public function customers(): Customers
    {
        return new Customers($this->api);
    }

    public function disputes(): Disputes
    {
        return new Disputes($this->api);
    }

    public function info(): Info
    {
        return new Info($this->api);
    }

    public function paymentLinks(): PaymentLinks
    {
        return new PaymentLinks($this->api);
    }

    public function paymentMethods(): PaymentMethods
    {
        return new PaymentMethods($this->api);
    }

    public function providers(): Providers
    {
        return new Providers($this->api);
    }

    public function settlements(): Settlements
    {
        return new Settlements($this->api);
    }

    public function subscriptions(): Subscriptions
    {
        return new Subscriptions($this->api);
    }

    public function tokens(): Tokens
    {
        return new Tokens($this->api);
    }

    public function transfers(): Transfers
    {
        return new Transfers($this->api);
    }

    public function vaults(): Vaults
    {
        return new Vaults($this->api);
    }

    public function webhookSubscriptions(): WebhookSubscriptions
    {
        return new WebhookSubscriptions($this->api);
    }

    /**
     * `GET /`: whether the API is up. Needs no key.
     */
    public function root(): RootStatus
    {
        return RootStatus::fromArray($this->api->get('/'));
    }

    /**
     * `GET /version`: the API's version and region.
     */
    public function version(): ApplicationVersion
    {
        return ApplicationVersion::fromArray($this->api->get('/version'));
    }
}
