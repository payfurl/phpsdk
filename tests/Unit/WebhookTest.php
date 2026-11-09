<?php

declare(strict_types=1);

namespace payFURL\Sdk\Tests\Unit;

use payFURL\Sdk\Exception\InvalidRequestException;
use payFURL\Sdk\Webhook;
use PHPUnit\Framework\TestCase;

final class WebhookTest extends TestCase
{
    // A transaction webhook signed with this key, as webhooks-sender sends it (X-Payfurl-Signature).
    private const KEY = 'dCM6l9ngZMJXVappk73yS607k1K7byfyzTTdToaKMa8=';
    private const SIGNATURE = 'rDYP2MxMKvvmoV2KrbOi4pnelHnVJoFYdBegvCK7IQk=';
    private const BODY = '{"data":{"chargeId":"3f83ab8fdf624c649bc70bbba81d6c2b","providerChargeId":"ch_3MYd2tE9mXU4onpB0r5iTsiL","amount":20,"providerId":"a26c371f-94f6-40da-add2-28ec8e9da8ed","paymentInformation":{"paymentMethodId":"80da8c2d674b4d2e8c65a6520e89d070","card":{"cardNumber":"4111********1111","expiryDate":"12/25","type":"VISA","cardType":"CREDIT","cardIin":"411"},"type":"CARD"},"customerId":"025c73d9cd0540e9a5a997f8ba97c732","status":"SUCCESS","dateAdded":"2023-02-06T22:20:19.0461561Z","successDate":"2023-02-06T22:20:20.8655832Z","estimatedCost":0.20,"estimatedCostCurrency":"AUD","currency":"Aud","refunds":[],"threeDsVerified":false},"meta":{"messageId":"bc4f056315d6e0205ab085dde45c4a46","timestamp":"2023-01-19T20:37:12.8456589Z","type":"transaction","eventType":"transaction.status.changed"}}';

    public function testValidSignature(): void
    {
        self::assertTrue(Webhook::verify(self::BODY, self::SIGNATURE, self::KEY));

        $event = Webhook::parse(self::BODY, self::SIGNATURE, self::KEY);
        self::assertIsArray($event['data']);
        self::assertSame('3f83ab8fdf624c649bc70bbba81d6c2b', $event['data']['chargeId']);
        self::assertIsArray($event['meta']);
        self::assertSame('transaction', $event['meta']['type']);
    }

    public function testTamperedBody(): void
    {
        self::assertFalse(Webhook::verify(str_replace('"amount":20', '"amount":2000', self::BODY), self::SIGNATURE, self::KEY));
    }

    public function testWrongKeyOrMissingSignature(): void
    {
        self::assertFalse(Webhook::verify(self::BODY, self::SIGNATURE, 'another key'));
        self::assertFalse(Webhook::verify(self::BODY, null, self::KEY));
        self::assertFalse(Webhook::verify(self::BODY, '', self::KEY));
        self::assertFalse(Webhook::verify(self::BODY, self::SIGNATURE, ''));
    }

    public function testParseRejectsAnUnsignedBody(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('not from payFURL');

        Webhook::parse(self::BODY, 'InvalidSignature', self::KEY);
    }

    public function testParseRejectsASignedBodyThatIsNotJson(): void
    {
        $body = 'not json';

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('not JSON');

        Webhook::parse($body, base64_encode(hash_hmac('sha256', $body, self::KEY, true)), self::KEY);
    }
}
