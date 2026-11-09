# Upgrading from 2.x to 3.0

3.0 is a new SDK on the same Composer package. It needs PHP 8.2 or later. Every 2.x class is gone, so code that
uses the SDK changes, but each 2.x call has a direct replacement (below). 2.x stays on Packagist: `^2.2` keeps
installing it until you move.

```bash
composer require payfurl/phpsdk:^3.0
```

## The client replaces `Config`

2.x configured the SDK once, globally, and each class was created with `new`:

```php
Config::initialise($secretKey, 'sandbox');
$charge = (new Charge())->CreateWithToken(['Amount' => 20, 'Currency' => 'AUD', 'Token' => $token]);
echo $charge['chargeId'];
```

3.0 has one client per key. A store with several payFURL accounts (Magento's websites) makes one client per
account:

```php
use payFURL\Sdk\Environment;
use payFURL\Sdk\Model\NewChargeToken;
use payFURL\Sdk\PayfurlClient;

$payfurl = new PayfurlClient($secretKey, Environment::Sandbox);
$charge = $payfurl->charges->createWithToken(new NewChargeToken(amount: 20.0, token: $token, currency: 'AUD'));
echo $charge->chargeId;
```

| 2.x | 3.0 |
| --- | --- |
| `Config::initialise($key, $environment)` | `new PayfurlClient($key, $environment)`; the environment is an `Environment` or its value (`'sandbox'`) |
| `Config::initialise(..., $timeoutMilliseconds)` | `new ClientOptions(timeoutMilliseconds: ...)`, the client's third argument |
| `Config::$EnableDebug` | none: pass a `Transport` that logs, wrapping `CurlTransport` |
| the `local` environment's fixed `https://localhost:5001` | still `Environment::Local`; `ClientOptions(baseUrl: ...)` points any environment elsewhere |

## Arrays in, models out

Every method takes a model instead of an array, with named arguments. A required field is a required argument,
so a missing one fails when the code runs, not at the API. The models are named as in the API (`NewChargeToken`
is the API's `NewChargeToken`), in `payFURL\Sdk\Model`.

Responses are models too, with typed properties. Code that reads arrays keeps working through `toArray()`, which
returns the response exactly as the API sent it:

```php
$charge = $payfurl->charges->get($chargeId);
$charge->status;              // TransactionStatus::Success
$charge->toArray()['status']; // 'SUCCESS', as 2.x returned it
```

A value 3.0 does not know yet, such as a new charge status, reads as `null` in the typed property and stays in
`toArray()`.

Keys in 2.x arrays could be PascalCase or camelCase. In 3.0 they are named arguments, always camelCase.

## Errors

`ResponseException` is replaced by subclasses of `PayfurlException`. Its properties keep their names:

| 2.x `ResponseException` | 3.0 `PayfurlException` |
| --- | --- |
| `getCode()` | `getCode()`, or `$errorCode` (`ErrorCode::tryFrom()` names it) |
| `$httpCode`, `$isRetryable`, `$type`, `$resource`, `$details` | the same, typed |
| none | `$gatewayCode`, `$gatewayMessage`, `$chargeId`, `$traceId` |
| HTTP code 408 for a timeout | `NetworkException` with `$timedOut` |
| `ErrorCode::Timeout` and the other constants | `ErrorCode` is an enum: `ErrorCode::Timeout->value` |
| `ErrorCode::urlFor($code)` | `ErrorCode::tryFrom($code)?->url()` (null for a code the enum does not have yet) |

Catch the subclass for the case: `AuthenticationException` (401, 403), `InvalidRequestException` (a validation
error, or an empty id before sending), `NotFoundException` (404), `ApiException` (a declined charge, a server
error), `NetworkException` (no response). 3.0 retries GETs itself; it never retries a charge.

## Every method

`$payfurl` is a `PayfurlClient`. Ids that 2.x took inside the array are arguments in 3.0.

| 2.x | 3.0 |
| --- | --- |
| `Charge::CreateWithCard` | `$payfurl->charges->createWithCard(new NewChargeCard(...))` |
| `Charge::CreateWithCardLeastCost` | `charges->createWithCardLeastCost(new NewChargeCardLeastCost(...))` |
| `Charge::CreateWithCustomer` | `charges->createWithCustomer(new NewChargeCustomer(...))` |
| `Charge::CreateWithPaymentMethod` | `charges->createWithPaymentMethod(new NewChargePaymentMethod(...))` |
| `Charge::CreateWithToken` | `charges->createWithToken(new NewChargeToken(...))` |
| `Charge::CreateWithBankAccount` | `charges->createWithBankAccount(new NewChargeBankPayment(...))` |
| `Charge::CreateWithNetworkToken` | `charges->createWithNetworkToken(new NewChargeNetworkToken(...))` |
| `Charge::CreateWithApplePay` | removed: `/charge/token/apple` takes only a public key, so it always failed. Apple Pay runs in the browser SDK |
| `Charge::Single(['ChargeId' => $id])` | `charges->get($id)` |
| `Charge::Search($params)` | `charges->search(new ChargeSearch(...))` |
| `Charge::Refund(['ChargeId' => $id, 'Amount' => $a, 'Comment' => $c])` | `charges->refund($id, $a, $c)` |
| `Charge::Capture(['ChargeId' => $id, 'Amount' => $a])` | `charges->capture($id, $a)` |
| `Charge::Void(['ChargeId' => $id])` | `charges->void($id)` |
| `Customer::CreateWithCard` | `customers->createWithCard(new NewCustomerCard(...))` |
| `Customer::CreateWithToken` | `customers->createWithToken(new NewCustomerToken(...))` |
| `Customer::CreateWithPayTo` | `customers->createWithPayTo(new NewCustomerPayTo(...))` |
| `Customer::CreateWithProviderToken` | `customers->createWithProviderToken(new NewCustomerProviderToken(...))` |
| `Customer::CreateWithSingleUseToken` | `customers->createWithSingleUseToken(new SingleUseToken(...))` |
| `Customer::CreateWithBankAccount` | `customers->createWithBankAccount(new NewCustomerBankPayment(...))` |
| `Customer::Single(['CustomerId' => $id])` | `customers->get($id)` |
| `Customer::Search($params)` | `customers->search(new CustomerSearch(...))` |
| `Customer::UpdateCustomer` | `customers->update($id, new UpdateCustomer(...))` |
| `Customer::RemoveCustomer(['CustomerId' => $id])` | `customers->delete($id)`; `gatewayDelete: true` also deletes at the gateways |
| `Customer::CustomerPaymentMethods(['CustomerId' => $id])` | `customers->paymentMethods($id)`: a list of `PaymentMethodData` |
| `Customer::CreatePaymentMethodWithCard` | `customers->addPaymentMethodWithCard($id, new NewPaymentMethodCard(...))` |
| `Customer::CreatePaymentMethodWithToken` | `customers->addPaymentMethodWithToken($id, new NewPaymentMethodToken(...))` |
| `Customer::CreatePaymentMethodWithPayTo` | `customers->addPaymentMethodWithPayTo($id, new NewPayToAgreement(...))` |
| `Customer::CreatePaymentMethodWithBankAccount` | `customers->addPaymentMethodWithBankAccount($id, new NewPaymentMethodBankPayment(...))` |
| `PaymentMethod::CreatePaymentMethodWithCard` | `paymentMethods->createWithCard(new NewPaymentMethodCard(...))` |
| `PaymentMethod::CreatePaymentMethodWithVault` | `paymentMethods->createWithVault(new NewPaymentMethodVault(...))` |
| `PaymentMethod::CreateWithPayTo` | `paymentMethods->createWithPayTo(new NewPayToAgreement(...))` |
| `PaymentMethod::CreateWithToken` | `paymentMethods->createWithToken(new NewPaymentMethodToken(...))` |
| `PaymentMethod::CreateWithSingleUseToken` | `paymentMethods->createWithSingleUseToken(new SingleUseToken(...))` |
| `PaymentMethod::CreateWithProviderToken` | `paymentMethods->createWithProviderToken(new NewProviderToken(...))` |
| `PaymentMethod::CreatePaymentMethodWithBankAccount` | `paymentMethods->createWithBankAccount(new NewPaymentMethodBankPayment(...))` |
| `PaymentMethod::Single(['PaymentMethodId' => $id])` | `paymentMethods->get($id)` |
| `PaymentMethod::Search($params)` | `paymentMethods->search(new PaymentMethodSearch(...))` |
| `PaymentMethod::UpdatePaymentMethod` | `paymentMethods->update($id, new UpdatePaymentMethod(...))` |
| `PaymentMethod::RemovePaymentMethod(['PaymentMethodId' => $id])` | `paymentMethods->delete($id)` |
| `Token::Single($id)` | `tokens->get($id)` |
| `Token::Search($params)` | `tokens->search(new TokenSearch(...))` |
| `Token::TokeniseCard`, `TokeniseCardLeastCost`, `TokenisePayTo` | removed: they take only a public key, so they always failed. Tokens are made in the browser |
| `Provider::Create` | `providers->create(new NewProvider(...))` |
| `Provider::Update($id, $params)` | `providers->update($id, new UpdateProvider(...))` |
| `Provider::Delete($id)` | `providers->delete($id)` |
| `Subscription::CreateSubscription` | `subscriptions->create(new SubscriptionCreate(...))` |
| `Subscription::GetSubscription(['SubscriptionId' => $id])` | `subscriptions->get($id)` |
| `Subscription::Search($params)` | `subscriptions->search(new SubscriptionSearch(...))` |
| `Subscription::UpdateSubscription($id, $params)` | `subscriptions->update($id, new SubscriptionUpdate(...))` |
| `Subscription::UpdateSubscriptionStatus($id, ['Status' => ...])` | `subscriptions->updateStatus($id, SubscriptionStatus::Suspended)` |
| `Subscription::DeleteSubscription(['SubscriptionId' => $id])` | `subscriptions->delete($id)` |
| `Batch::CreateTransactionWithPaymentMethod` | `batches->createWithPaymentMethods(new NewTransactionPaymentMethod(...))` |
| `Batch::GetBatch(['BatchId' => $id])` | `batches->get($id)` |
| `Batch::GetBatchStatus(['BatchId' => $id])` | `batches->status($id)` |
| `Batch::Search($params)` | `batches->search(new BatchSearch(...))` |
| `PaymentLink::Create` | `paymentLinks->create(new CreatePaymentLink(...))` |
| `PaymentLink::Single($id)` | `paymentLinks->get($id)` |
| `PaymentLink::Search($params)` | `paymentLinks->search(new PaymentLinkSearch(...))` |
| `PaymentLink::EncodeImage($path)` | `PaymentLinks::imageDataUri($path)` (static) |
| `Transfer::Create` | `transfers->create(new NewTransferGroup(...))`; `chargeId` is optional now |
| `Transfer::Single($id)` | `transfers->get($id)` |
| `Transfer::Search($params)` | `transfers->search(new TransferSearch(...))` |
| `Vault::Create` | `vaults->create(new NewVault(...))` |
| `Vault::Single($id)` | `vaults->get($id)` |
| `Vault::Delete($id)` | `vaults->delete($id)` |
| `WebhookSubscription::Create` | `webhookSubscriptions->create(new NewWebhookSubscription(...))` |
| `WebhookSubscription::Single($id)` | `webhookSubscriptions->get($id)` |
| `WebhookSubscription::Search($params)` | `webhookSubscriptions->search(new WebhookSubscriptionSearch(...))` |
| `WebhookSubscription::Delete($id)` | `webhookSubscriptions->delete($id)` |
| `Settlement::GetSettlements(['StartDate' => ..., 'EndDate' => ...])` | `settlements->search(new DateTimeImmutable(...), ...)` |
| `Info::Providers(['Amount' => ..., 'Currency' => ...])` | `info->providers($amount, $currency, $browserSdkVersion)`; see below |
| `Info::Info()` | removed: `/info` is not a public endpoint |
| `Info::DefaultFallback()` | removed: `/info/default_fallback_provider` is not a public endpoint |
| `Root::Get()` | `$payfurl->root()` |
| `Version::Get()` | `$payfurl->version()` |
| `WebhookTools::DeserializeTransaction($body, $signature, $key)` | `Webhook::parse($body, $signature, $key)`: an array, not an object; `Webhook::verify()` only checks |
| `HttpWrapper`, `UrlTools`, `ArrayTools`, `CaseConverter`, `Region` | removed: internals |

New in 3.0: `disputes->search()` and `disputes->get($id)`.

## `/info/providers`

2.x sent `sdk-version: 4.5.7` on every call, and the API lists PayPal providers only for a browser SDK of
version 4 or later. 3.0 sends the version you pass as `$browserSdkVersion`: the version of `@payfurl/client` your
checkout loads. Leave it null and the response has no PayPal providers.

The endpoint also takes the public key, which 2.x could only call through `HttpWrapper` with a made-up secret
key. In 3.0 make a client with the public key:

```php
$providers = (new PayfurlClient($publicKey, Environment::Sandbox))->info->providers(browserSdkVersion: '4.11.0');
```

A client with a public key can call nothing else.
