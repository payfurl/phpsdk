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

Before running the tests, create `config.json` file in `tests` folder with the following contents:

```json
{
  "Environment": "Development",
  "SecretKey": "PAYFURL_SECRET_KEY",
  "ProviderId": "DUMMY_PROVIDER_ID",
  "Tokens": ["PAYMENT_TOKEN1","PAYMENT_TOKEN2"]
}
```

We recommend to have 8 payment tokens to make all tests passed.

Then run `phpunit tests`
