# Changelog

Every release of `payfurl/phpsdk`. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and versions follow [Semantic Versioning](https://semver.org/spec/v2.0.0.html). Entries before 2.3.0 were written
back from the tags and merged pull requests.

## [Unreleased]

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

[Unreleased]: https://github.com/payfurl/phpsdk/compare/v2.3.0...HEAD
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
