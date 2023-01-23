# PayFURL PHP SDK

Library for integrating with PayFURL payments in your app. It includes the following APIs:

1. Charge API
2. Customer API
3. PaymentMethod API
4. Transfer API
5. Vault API
6. Token API
7. Provider API

## 📄 Requirements

Use of the PayFURL PHP SDK requires:

* PHP 7.4 or higher
* PHPUnit

# Running tests

To run the tests, ensure you have phpunit installed.

Then:
- modify TestBase.php to set the "CardProviderId", "PaypalProviderId" and the "secretKey".
- run phpunit tests
