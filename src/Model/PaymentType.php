<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

/**
 * A payment method's type. The API's `PaymentType` (_shared/src/api.core/Data/PaymentMethod.cs).
 */
enum PaymentType: string
{
    case Card = 'CARD';
    case PayPal = 'PAYPAL';
    case PayTo = 'PAYTO';
    case PayId = 'PAYID';
    case Optty = 'OPTTY';
    case GooglePay = 'GOOGLEPAY';
    case ApplePay = 'APPLEPAY';
    case Vis = 'VIS';
    case Shift = 'SHIFT';
    case Pesapal = 'PESAPAL';
    case PayByAccount = 'PAYBYACCOUNT';
    case ClickToPay = 'CLICK2PAY';
    case Upi = 'UPI';
    case PayGlocal = 'PAYGLOCAL';
    case BankPayment = 'BANKPAYMENT';
    case PaymentMethod = 'PAYMENT_METHOD';
    case Zip = 'ZIP';
}
