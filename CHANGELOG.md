# Changelog

Every release of `payfurl/phpsdk`. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and versions follow [Semantic Versioning](https://semver.org/spec/v2.0.0.html). Entries before 2.3.0 were written
back from the tags and merged pull requests.

## [Unreleased]

## [3.0.0] - 2026-11-09

A new SDK on the same package: typed, with a client object instead of static configuration. Nothing of 2.x's
API is left. [UPGRADE.md](UPGRADE.md) maps every 2.x method to its replacement; 2.x stays on Packagist for
code that has not moved.

### Added

- `PayfurlClient`: one client per key and environment, with a resource per part of the API (`charges()`,
  `customers()`, `paymentMethods()`, ...). `ClientOptions` sets the base URL, timeout, retries and transport.
- Typed requests and responses: a readonly model per API request and response (`NewChargeToken`, `ChargeData`,
  ...) under `payFURL\Sdk\Model`, with the API's own names. Responses keep the API's array in `toArray()`.
- Enums: `TransactionStatus`, `PaymentType`, `Initiator`, `SubscriptionInterval`, `SubscriptionRetryInterval`,
  `SubscriptionStatus`, `CallToAction`, `WebhookType`, and `ErrorCode` (int-backed).
- Exceptions under `PayfurlException`: `AuthenticationException`, `InvalidRequestException`,
  `NotFoundException`, `ApiException`, `NetworkException`, with the API error's code, type, details, gateway
  code and message, charge id and trace id.
- Retries: a GET is retried, with backoff, after a network error, a timeout or a retryable API error. Nothing
  else is retried, since the API has no idempotency keys.
- `Transport`, the HTTP seam: `CurlTransport` by default, `FakeTransport` for tests.
- Disputes: `disputes()->search()` and `get()` (`GET /dispute`, `GET /dispute/{disputeId}`).
- `info()->providers()` takes a public key too (sent as `x-publickey`), the card provider (`cardProviderId`)
  and the browser SDK version the checkout runs (`sdk-version`, which decides whether PayPal is listed).
- `gatewayDelete` on `customers()->delete()` and `paymentMethods()->delete()`.
- `Webhook::verify()` and `Webhook::parse()`.
- Every request sends `User-Agent: payfurl-php/<version> PHP/<version>`.

### Changed

- PHP 8.2 or later, with `declare(strict_types=1)` and types everywhere.
- Autoloading is PSR-4 only: the `require_once` calls are gone.
- Method names are camelCase, and each takes its model instead of an array (`Charge::CreateWithToken($params)`
  is `charges()->createWithToken(new NewChargeToken(...))`).
- Requests send the fields each endpoint's API model has, and only those. 2.x shared one builder for every
  charge, so it sent fields some endpoints ignore (`UserAgent` everywhere; `Recurring` and `Transfer` on card
  charges).
- Request bodies and query strings use the API's camelCase names; the API reads either case.
- `transfers()->create()` no longer requires a charge id: a transfer group can stand alone (PAY-4879).
- A webhook with a missing or wrong signature throws `InvalidRequestException` (was `InvalidArgumentException`).

### Removed

- `Config`: pass the key and environment to `PayfurlClient`. `Config::$EnableDebug` is gone: wrap the transport
  to log requests.
- `ResponseException`: see the exceptions above.
- Methods that always failed, because their endpoints take only a public key: `Token::TokeniseCard()`,
  `Token::TokeniseCardLeastCost()`, `Token::TokenisePayTo()` and `Charge::CreateWithApplePay()`. Tokens are made
  in the browser, by the browser SDK.
- Methods on endpoints that are not public: `Info::Info()` (`/info`) and `Info::DefaultFallback()`
  (`/info/default_fallback_provider`).
- The internals: `HttpWrapper`, `UrlTools`, `ArrayTools`, `CaseConverter`, `Region` and `WebhookTools`.
- The `sdk-version: 4.5.7` header on every `/info/providers` call: the caller passes its browser SDK version.
- The integration tests: they run in payfurl/platform now (`just e2e phpsdk`), against an API built from source.

## [2.3.0] - 2026-11-09

### Added

- `ErrorCode`: the API's error codes as constants, with `ErrorCode::urlFor()` for their documentation link
  (PAY-4450).
- `ResponseException` carries the API error's `type`, `resource` and `details`.
- `Root::Get()` (`GET /`), `Version::Get()` (`GET /version`) and `Settlement::GetSettlements()`
  (`GET /settlements`).
- `Charge::CreateWithNetworkToken()` (`POST /charge/network_token`).
- More request fields on charges, customers and payment methods.

### Fixed

- `WebhookTools` compares webhook signatures in constant time (`hash_equals`), and rejects a missing signature.
- With `Config::$EnableDebug` on, the printed request and response no longer show card numbers (only the last
  four digits) or CVVs.
- `curl_close()` is called only before PHP 8.0, where it is not deprecated.

## [2.2.3] - 2025-08-05

### Added

- `Metadata` on payment links (PAY-4285).

## [2.2.2] - 2025-07-07

### Added

- Payment link helpers, such as `PaymentLink::EncodeImage()` (PAY-4272).
- `Metadata` on batch transactions (PAY-4249).

## [2.2.1] - 2025-06-13

### Added

- Payment links: `PaymentLink::Create()`, `Single()` and `Search()` (PAY-4198).
- The `Recurring` flag on charges (PAY-4219).

## [2.2.0] - 2025-05-01

### Changed

- CI pins PHPUnit 9.5 on PHP 7.4.

## [2.1.6] - 2025-05-01

### Added

- Centralised webhook subscriptions: `WebhookSubscription` (PAY-3675).

### Removed

- The `us` region in sandbox and the `jp` region in development (PAY-3996).

## [2.1.5] - 2024-12-17

### Added

- `Subscription::UpdateSubscription()` (PAY-3638) and `Subscription::UpdateSubscriptionStatus()`, to pause a
  subscription (PAY-3640).
- Parameters the API accepted but the SDK did not send (PAY-3449).

## [2.1.4] - 2024-11-06

### Added

- `Metadata` on subscriptions (PAY-3402).

### Fixed

- Charges send the customer's email and phone (PAY-3412).

## [2.1.3] - 2024-09-16

### Added

- `PaymentMethod::UpdatePaymentMethod()` (PAY-3262).
- New search parameters, including `CardType` on payment methods (PAY-3304, PAY-3323).

### Fixed

- The `Address` and `Order` of a charge.

## [2.1.2] - 2024-07-24

### Added

- A webhook on batch transactions.

## [2.1.1] - 2024-07-16

### Added

- Subscriptions: `Subscription` (PAY-3041).
- New charge parameters (PAY-3104).

## [2.1.0] - 2024-06-21

### Added

- Bank account (direct debit) customers, payment methods and charges (PAY-2869).
- `PaymentMethod::CreateWithProviderToken()` (`POST /payment_method/provider_token`, PAY-2909).
- camelCase parameter names, converted to the API's PascalCase (PAY-2933).

[Unreleased]: https://github.com/payfurl/phpsdk/compare/v3.0.0...HEAD
[3.0.0]: https://github.com/payfurl/phpsdk/compare/v2.3.0...v3.0.0
[2.3.0]: https://github.com/payfurl/phpsdk/compare/v2.2.3...v2.3.0
[2.2.3]: https://github.com/payfurl/phpsdk/compare/v2.2.2...v2.2.3
[2.2.2]: https://github.com/payfurl/phpsdk/compare/v2.2.1...v2.2.2
[2.2.1]: https://github.com/payfurl/phpsdk/compare/v2.2.0...v2.2.1
[2.2.0]: https://github.com/payfurl/phpsdk/compare/v2.1.6...v2.2.0
[2.1.6]: https://github.com/payfurl/phpsdk/compare/v2.1.5...v2.1.6
[2.1.5]: https://github.com/payfurl/phpsdk/compare/v2.1.4...v2.1.5
[2.1.4]: https://github.com/payfurl/phpsdk/compare/v2.1.3...v2.1.4
[2.1.3]: https://github.com/payfurl/phpsdk/compare/v2.1.2...v2.1.3
[2.1.2]: https://github.com/payfurl/phpsdk/compare/v2.1.1...v2.1.2
[2.1.1]: https://github.com/payfurl/phpsdk/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/payfurl/phpsdk/compare/v2.0.0...v2.1.0
