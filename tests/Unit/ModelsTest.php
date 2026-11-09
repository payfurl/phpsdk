<?php

declare(strict_types=1);

namespace payFURL\Sdk\Tests\Unit;

use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\Environment;
use payFURL\Sdk\Exception\InvalidRequestException;
use payFURL\Sdk\Http\FakeTransport;
use payFURL\Sdk\Model\ChargeData;
use payFURL\Sdk\Model\NewChargeToken;
use payFURL\Sdk\Model\NewPaymentMethodToken;
use payFURL\Sdk\Model\PaymentType;
use payFURL\Sdk\Model\Subscription;
use payFURL\Sdk\Model\SubscriptionInterval;
use payFURL\Sdk\Model\SubscriptionStatus;
use payFURL\Sdk\Model\TransactionStatus;
use payFURL\Sdk\Model\WebhookSubscription;
use payFURL\Sdk\Model\WebhookType;
use payFURL\Sdk\PayfurlClient;
use payFURL\Sdk\Resource\PaymentLinks;
use PHPUnit\Framework\TestCase;

final class ModelsTest extends TestCase
{
    /** A charge as POST /charge/token returns it (ChargeData, camelCase, enums as names). */
    private const CHARGE = [
        'chargeId' => '3f83ab8fdf624c649bc70bbba81d6c2b',
        'providerChargeId' => 'ch_3MYd2tE9mXU4onpB0r5iTsiL',
        'amount' => 20,
        'providerId' => 'a26c371f-94f6-40da-add2-28ec8e9da8ed',
        'paymentInformation' => [
            'paymentMethodId' => '80da8c2d674b4d2e8c65a6520e89d070',
            'card' => ['cardNumber' => '4111********1111', 'expiryDate' => '12/25', 'type' => 'VISA', 'cardType' => 'CREDIT', 'cardIin' => '411'],
            'type' => 'CARD',
        ],
        'customerId' => '025c73d9cd0540e9a5a997f8ba97c732',
        'status' => 'SUCCESS',
        'dateAdded' => '2023-02-06T22:20:19.0461561Z',
        'estimatedCost' => 0.20,
        'currency' => 'Aud',
        'refunds' => [['amount' => 5, 'comment' => 'damaged', 'dateAdded' => '2023-02-07T01:00:00Z']],
        'threeDsVerified' => false,
        'metadata' => ['order' => '42'],
        'someFieldThisSdkDoesNotKnow' => 1,
    ];

    public function testResponseMapsToTheModel(): void
    {
        $charge = ChargeData::fromArray(self::CHARGE);

        self::assertSame('3f83ab8fdf624c649bc70bbba81d6c2b', $charge->chargeId);
        self::assertSame(20.0, $charge->amount);
        self::assertSame(TransactionStatus::Success, $charge->status);
        self::assertSame(PaymentType::Card, $charge->paymentInformation?->type);
        self::assertSame('4111********1111', $charge->paymentInformation->card?->cardNumber);
        self::assertSame('2023-02-06 22:20:19.046156', $charge->dateAdded?->format('Y-m-d H:i:s.u'));
        self::assertNull($charge->successDate);
        self::assertSame(5.0, $charge->refunds[0]->amount ?? null);
        self::assertFalse($charge->threeDsVerified);
        self::assertSame(['order' => '42'], $charge->metadata);
        self::assertSame(self::CHARGE, $charge->toArray(), 'toArray() is the response as sent, unknown fields included');
    }

    public function testValueThisSdkDoesNotKnowReadsAsNullButStaysInTheArray(): void
    {
        $charge = ChargeData::fromArray(['status' => 'SOMETHING_NEW', 'amount' => 'not a number', 'dateAdded' => 'not a date']);

        self::assertNull($charge->status);
        self::assertNull($charge->amount);
        self::assertNull($charge->dateAdded);
        self::assertSame('SOMETHING_NEW', $charge->toArray()['status']);
    }

    public function testEnumsInResponses(): void
    {
        $subscription = Subscription::fromArray(['interval' => 'Month', 'status' => 'Suspended', 'retry' => ['interval' => 'Hour', 'maximum' => 3]]);
        $webhook = WebhookSubscription::fromArray(['types' => ['Transaction', 'Unknown', 'Dispute']]);

        self::assertSame(SubscriptionInterval::Month, $subscription->interval);
        self::assertSame(SubscriptionStatus::Suspended, $subscription->status);
        self::assertSame(3, $subscription->retry?->maximum);
        self::assertSame([WebhookType::Transaction, WebhookType::Dispute], $webhook->types);
    }

    public function testRequestLeavesOutNullsButSendsFalseAndZero(): void
    {
        $charge = new NewChargeToken(amount: 0.0, token: 't1', capture: false, reference: '');

        self::assertSame(['amount' => 0.0, 'token' => 't1', 'reference' => '', 'capture' => false], $charge->toArray());
    }

    public function testEmptyMetadataIsAJsonObject(): void
    {
        $transport = (new FakeTransport())->queue(201, []);
        $client = new PayfurlClient('sectest0-au', Environment::Sandbox, new ClientOptions(transport: $transport));

        $client->paymentMethods()->createWithToken(new NewPaymentMethodToken(token: 't1', metadata: []));

        self::assertSame('{"token":"t1","metadata":{}}', $transport->lastRequest()->body);
    }

    public function testAmountKeepsItsDecimalPoint(): void
    {
        $transport = (new FakeTransport())->queue(201, []);
        $client = new PayfurlClient('sectest0-au', Environment::Sandbox, new ClientOptions(transport: $transport));

        $client->charges()->createWithToken(new NewChargeToken(amount: 10, token: 't1'));

        self::assertSame('{"amount":10.0,"token":"t1"}', $transport->lastRequest()->body);
    }

    public function testImageDataUri(): void
    {
        $uri = PaymentLinks::imageDataUri(__DIR__ . '/../fixtures/100x50.png');

        self::assertStringStartsWith('data:image/png;base64,iVBORw0KGgo', $uri);
    }

    public function testImageDataUriRejectsOtherFiles(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('is not a PNG, JPEG or GIF image');

        PaymentLinks::imageDataUri(__FILE__);
    }

    public function testImageDataUriRejectsAMissingFile(): void
    {
        $this->expectException(InvalidRequestException::class);

        PaymentLinks::imageDataUri(__DIR__ . '/missing.png');
    }
}
