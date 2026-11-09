# payFURL PHP SDK

The [payFURL](https://payfurl.com) API for PHP: take payments on 60+ gateways through one integration. The SDK
covers the public REST API with the account's secret key: charges, customers, payment methods, subscriptions,
batches, payment links, transfers, vaults, providers, webhook subscriptions, settlements and disputes. API
reference: [docs.payfurl.com](https://docs.payfurl.com).

> **3.0 is a breaking release.** It is typed, needs PHP 8.2, and replaces 2.x's static classes with a client.
> [UPGRADE.md](UPGRADE.md) maps every 2.x method to its replacement. 2.x stays installable with `^2.3`.

## Install

```bash
composer require payfurl/phpsdk:^3.0
```

It needs PHP 8.2 or later with the `curl` and `json` extensions, and nothing else: no other Composer package, so
it can be bundled in a plugin without clashing with another plugin's copy of one.

### Supported PHP versions

Every PHP version still supported upstream: 8.2, 8.3, 8.4 and 8.5 today, each one tested in CI. Support for a
PHP version ends in the first minor release after its end of life, and the changelog says so.

## Quick start

```php
use payFURL\Sdk\Environment;
use payFURL\Sdk\Model\NewChargeToken;
use payFURL\Sdk\PayfurlClient;

$payfurl = new PayfurlClient(getenv('PAYFURL_SECRET_KEY'), Environment::Sandbox);

// $token comes from the browser SDK (@payfurl/client), which collects the card on your checkout page.
$charge = $payfurl->charges()->createWithToken(new NewChargeToken(
    amount: 20.0,
    token: $token,
    currency: 'AUD',
    reference: 'order-1001',
));

echo $charge->chargeId, ' ', $charge->status?->value; // 3f83ab8f... SUCCESS
```

Requests are models with named arguments: a required field is a required argument. They are named as in the API
(`NewChargeToken` is the API's `NewChargeToken`) and live in `payFURL\Sdk\Model`. Responses are models with
typed properties, every one nullable; `toArray()` returns the response as the API sent it.

## Environments and regions

| `Environment` | Host |
| --- | --- |
| `Production` | `https://api.payfurl.com`, or the region's own (`https://api-au.payfurl.com`) |
| `Sandbox` | `https://sandbox-api.payfurl.com`, or `https://sandbox-api-au.payfurl.com` |
| `Development` | payFURL's own development environment |
| `Local` | `https://localhost:5001`, an API running on this machine, with TLS verification off |

The region comes from the key: its last part (`sectest...-au`) is the account's region. `ClientOptions` changes
the rest:

```php
use payFURL\Sdk\ClientOptions;

$payfurl = new PayfurlClient($secretKey, 'production', new ClientOptions(
    baseUrl: null,               // another host, such as a proxy
    timeoutMilliseconds: 30000,  // per request; 60 seconds by default
    maxRetries: 2,               // GETs only, see below
));
```

One client per key: a store with several payFURL accounts makes one client per account.

## Examples

```php
use payFURL\Sdk\Model\{CardRequestInformation, ChargeSearch, DisputeSearch, NewChargePaymentMethod,
    NewCustomerToken, NewPaymentMethodCard, NewWebhookSubscription, SubscriptionCreate, SubscriptionInterval,
    SubscriptionStatus, TransactionStatus, WebhookType};

// Charges: refund part of one, capture an authorised one, search
$payfurl->charges()->refund($chargeId, amount: 5.0, comment: 'damaged');
$payfurl->charges()->capture($authorisedChargeId);
$list = $payfurl->charges()->search(new ChargeSearch(status: TransactionStatus::Success, limit: 50));

// Customers and saved payment methods
$customer = $payfurl->customers()->createWithToken(new NewCustomerToken(token: $token, email: 'jo@example.com'));
$paymentMethods = $payfurl->customers()->paymentMethods($customer->customerId);
$payfurl->charges()->createWithPaymentMethod(new NewChargePaymentMethod(amount: 15.0, paymentMethodId: $paymentMethods[0]->paymentMethodId));

// A card saved without a customer, on a named gateway
$payfurl->paymentMethods()->createWithCard(new NewPaymentMethodCard(
    providerId: $providerId,
    paymentInformation: new CardRequestInformation(cardNumber: '4111111111111111', expiryDate: '12/30', ccv: '123'),
));

// Subscriptions: monthly, then paused
$subscription = $payfurl->subscriptions()->create(new SubscriptionCreate(
    amount: 9.95, paymentMethodId: $paymentMethodId, currency: 'AUD', interval: SubscriptionInterval::Month, frequency: 1,
));
$payfurl->subscriptions()->updateStatus($subscription->subscriptionId, SubscriptionStatus::Suspended);

// Webhooks: where they go
$payfurl->webhookSubscriptions()->create(new NewWebhookSubscription(url: 'https://shop.example/payfurl', types: [WebhookType::Transaction]));

// Disputes, settlements, what the checkout can show
$disputes = $payfurl->disputes()->search(new DisputeSearch(dueBefore: new DateTimeImmutable('+7 days')));
$settlements = $payfurl->settlements()->search(new DateTimeImmutable('first day of last month'));
$providers = $payfurl->info()->providers(amount: 20.0, currency: 'AUD', browserSdkVersion: '4.11.0');
```

| Resource | Methods |
| --- | --- |
| `charges()` | `createWithCard`, `createWithCardLeastCost`, `createWithCustomer`, `createWithPaymentMethod`, `createWithToken`, `createWithBankAccount`, `createWithNetworkToken`, `get`, `search`, `refund`, `capture`, `void` |
| `customers()` | `createWithCard`, `createWithToken`, `createWithPayTo`, `createWithProviderToken`, `createWithSingleUseToken`, `createWithBankAccount`, `get`, `search`, `update`, `delete`, `paymentMethods`, `addPaymentMethodWithCard`, `addPaymentMethodWithToken`, `addPaymentMethodWithPayTo`, `addPaymentMethodWithBankAccount` |
| `paymentMethods()` | `createWithCard`, `createWithVault`, `createWithPayTo`, `createWithToken`, `createWithSingleUseToken`, `createWithProviderToken`, `createWithBankAccount`, `get`, `search`, `update`, `delete` |
| `subscriptions()` | `create`, `get`, `search`, `update`, `updateStatus`, `delete` |
| `batches()` | `createWithPaymentMethods`, `get`, `status`, `search` |
| `paymentLinks()` | `create`, `get`, `search`, and the static `imageDataUri` |
| `transfers()` | `create`, `get`, `search` |
| `vaults()` | `create`, `get`, `delete` |
| `providers()` | `create`, `update`, `delete` |
| `tokens()` | `get`, `search` |
| `webhookSubscriptions()` | `create`, `get`, `search`, `delete` |
| `settlements()` | `search` |
| `disputes()` | `get`, `search` |
| `info()` | `providers` (takes the public key too) |
| the client | `root`, `version` |

Tokens are made in the browser with the public key, by the browser SDK; the server SDK charges them.

## Errors and retries

Every error is a `payFURL\Sdk\Exception\PayfurlException`:

| Exception | When |
| --- | --- |
| `AuthenticationException` | the key is wrong or may not call the endpoint (HTTP 401, 403) |
| `InvalidRequestException` | the API rejected the fields (HTTP 400, `ErrorCode::ValidationError`; `$details` names them), or an id was empty |
| `NotFoundException` | no such resource (HTTP 404) |
| `ApiException` | any other API error: a declined charge (HTTP 400 with its code), a server error (5xx) |
| `NetworkException` | no response: a network error, or a timeout (`$timedOut`) |

```php
use payFURL\Sdk\ErrorCode;
use payFURL\Sdk\Exception\ApiException;
use payFURL\Sdk\Exception\NetworkException;

try {
    $charge = $payfurl->charges()->createWithToken($request);
} catch (ApiException $e) {
    // $e->httpCode, $e->errorCode (ErrorCode::tryFrom($e->errorCode)), $e->isRetryable, $e->details,
    // $e->gatewayCode, $e->gatewayMessage, $e->chargeId (the declined charge), $e->traceId (for support)
} catch (NetworkException $e) {
    // The charge may still have gone through: search by its reference before charging again.
}
```

A GET is retried, up to `ClientOptions::$maxRetries` times with a doubling delay, after a network error, a
timeout or an error the API marks retryable. Nothing else is retried: the API has no idempotency keys, so a
retried charge could charge twice.

## Webhooks

payFURL signs each webhook with the account's webhook signing key (the portal's Webhook Secret Key) and sends the
signature in `X-Payfurl-Signature`. Check it before trusting the body:

```php
use payFURL\Sdk\Model\ChargeData;
use payFURL\Sdk\Webhook;

$body = file_get_contents('php://input');
$event = Webhook::parse($body, $_SERVER['HTTP_X_PAYFURL_SIGNATURE'] ?? null, $signingKey); // throws if it is not payFURL's
if ($event['meta']['type'] === 'transaction') {
    $charge = ChargeData::fromArray($event['data']);
}
```

`Webhook::verify()` returns a bool instead. Both compare in constant time.

## Testing your code

`FakeTransport` stands in for the network: queue the API's responses, then check what your code sent.

```php
use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\Http\FakeTransport;

$transport = (new FakeTransport())->queue(201, ['chargeId' => 'ch1', 'status' => 'SUCCESS']);
$payfurl = new PayfurlClient('sectest0-au', Environment::Sandbox, new ClientOptions(transport: $transport));

$checkout->pay($payfurl); // your code

$transport->lastRequest()->url;   // https://sandbox-api-au.payfurl.com/charge/token
$transport->lastBody()['amount']; // 20.0
```

`queueFailure()` makes the next request fail as a network error or a timeout. Any other `Transport` works too,
such as one that logs each request before handing it to `CurlTransport`.

## Development

[`just`](https://just.systems) runs everything:

```bash
just test   # PHPUnit, PHPStan (max level), PHP CS Fixer (PER-CS), composer validate and audit
just fix    # apply the code style
just e2e    # the integration tests, against an API built from source (below)
```

The unit tests (`tests/Unit`) never call an API: each request goes through `FakeTransport`. The integration tests
live in payFURL's platform repository (`e2e/api-tests/phpsdk`, internal): `just e2e` runs them from a platform
checkout at `../platform` or `../../platform` (or `PLATFORM_DIR`) against this checkout, in Docker, on a fresh
merchant account each run.

The models in `src/Model` mirror the API's C# models (`services/api/src/api/Models` and `services/_shared/src`
in the platform repository), with the same names. When the API changes a model, change the SDK's to match.

## Releasing

1. Add the version's section to [CHANGELOG.md](CHANGELOG.md) and set `PayfurlClient::VERSION` to it; merge to
   `master`.
2. Tag the merge `vX.Y.Z` and push the tag.

The **Release** workflow (`.github/workflows/release.yml`) then runs CI, checks the tag against
`PayfurlClient::VERSION`, creates the GitHub release from the changelog section, and tells Packagist to update
(GitHub secrets `PACKAGIST_USERNAME` and `PACKAGIST_TOKEN`: the maintainer's username and API token from
packagist.org). Packagist reads the version from the tag; `composer.json` has no `version`.

Versions follow semver strictly: plugins resolve `^3.0` when a merchant installs them, so every 3.x release
reaches stores without a plugin release.

## License

MIT, see [LICENSE](LICENSE).
