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
 *     $charge = $payfurl->charges->createWithToken(new NewChargeToken(amount: 20.0, token: $token, currency: 'AUD'));
 *
 * A secret key (`sec...`) calls every endpoint. A public key (`pub...`) calls only `info->providers()`. One client
 * per key: a store with several accounts makes one for each.
 */
final class PayfurlClient
{
    /** This SDK's version: the release tag without its `v`, and in the User-Agent of every request. */
    public const VERSION = '3.0.0';

    private readonly ApiClient $api;

    // One per part of the API: `$payfurl->charges->createWithToken(...)`. Properties, not methods, so they read as
    // the API's parts rather than as calls that fetch charges.
    public readonly Batches $batches;
    public readonly Charges $charges;
    public readonly Customers $customers;
    public readonly Disputes $disputes;
    public readonly Info $info;
    public readonly PaymentLinks $paymentLinks;
    public readonly PaymentMethods $paymentMethods;
    public readonly Providers $providers;
    public readonly Settlements $settlements;
    public readonly Subscriptions $subscriptions;
    public readonly Tokens $tokens;
    public readonly Transfers $transfers;
    public readonly Vaults $vaults;
    public readonly WebhookSubscriptions $webhookSubscriptions;

    /**
     * @param string $key the account's secret key, or its public key for `info->providers()` only
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
        $this->batches = new Batches($this->api);
        $this->charges = new Charges($this->api);
        $this->customers = new Customers($this->api);
        $this->disputes = new Disputes($this->api);
        $this->info = new Info($this->api);
        $this->paymentLinks = new PaymentLinks($this->api);
        $this->paymentMethods = new PaymentMethods($this->api);
        $this->providers = new Providers($this->api);
        $this->settlements = new Settlements($this->api);
        $this->subscriptions = new Subscriptions($this->api);
        $this->tokens = new Tokens($this->api);
        $this->transfers = new Transfers($this->api);
        $this->vaults = new Vaults($this->api);
        $this->webhookSubscriptions = new WebhookSubscriptions($this->api);
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
