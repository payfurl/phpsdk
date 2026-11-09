<?php

declare(strict_types=1);

namespace payFURL\Sdk\Tests\Unit;

use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\Environment;
use payFURL\Sdk\ErrorCode;
use payFURL\Sdk\Exception\ApiException;
use payFURL\Sdk\Exception\AuthenticationException;
use payFURL\Sdk\Exception\InvalidRequestException;
use payFURL\Sdk\Exception\NetworkException;
use payFURL\Sdk\Exception\NotFoundException;
use payFURL\Sdk\Exception\PayfurlException;
use payFURL\Sdk\Http\FakeTransport;
use payFURL\Sdk\Model\NewChargeToken;
use payFURL\Sdk\PayfurlClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ErrorsTest extends TestCase
{
    private FakeTransport $transport;
    private PayfurlClient $client;

    protected function setUp(): void
    {
        $this->transport = new FakeTransport();
        $this->client = new PayfurlClient(
            'sectest0-au',
            Environment::Sandbox,
            new ClientOptions(maxRetries: 2, retryDelayMilliseconds: 0, transport: $this->transport),
        );
    }

    /**
     * @param array<array-key, mixed>|string $body
     * @param class-string<PayfurlException> $expected
     */
    #[DataProvider('errors')]
    public function testErrorResponsesBecomeExceptions(int $status, array|string $body, string $expected, string $message): void
    {
        $this->transport->queue($status, $body);

        try {
            $this->client->charges()->createWithToken(new NewChargeToken(amount: 10.0, token: 't1'));
            self::fail('no exception');
        } catch (PayfurlException $e) {
            self::assertInstanceOf($expected, $e);
            self::assertSame($message, $e->getMessage());
            self::assertSame($status, $e->httpCode);
        }
    }

    /**
     * @return iterable<string, array{int, array<array-key, mixed>|string, class-string<PayfurlException>, string}>
     */
    public static function errors(): iterable
    {
        yield 'bad key' => [401, '', AuthenticationException::class, 'HTTP 401 from the payFURL API'];
        yield 'public key on a secret-key endpoint' => [403, ['message' => 'Forbidden'], AuthenticationException::class, 'Forbidden'];
        yield 'not found' => [404, ['message' => 'Charge not found', 'code' => 50], NotFoundException::class, 'Charge not found'];
        yield 'validation' => [400, ['message' => 'Invalid', 'code' => ErrorCode::ValidationError->value, 'details' => ['Amount' => 'too small']],
            InvalidRequestException::class, 'Invalid'];
        yield 'decline' => [400, ['message' => 'Card declined', 'code' => 103], ApiException::class, 'Card declined'];
        yield 'server error' => [500, '<html>oops</html>', ApiException::class, '<html>oops</html>'];
        yield 'plain text bad request' => [400, "Invalid start date format. Please use 'yyyy-MM-dd'.", ApiException::class,
            "Invalid start date format. Please use 'yyyy-MM-dd'."];
    }

    public function testErrorFieldsAreKept(): void
    {
        $this->transport->queue(400, ['message' => 'Declined', 'code' => 103, 'isRetryable' => false, 'type' => 'https://docs.payfurl.com/errorcodes#103',
            'resource' => '/charge/token', 'details' => ['Card' => 'declined'], 'gatewayCode' => '05', 'gatewayMessage' => 'Do not honour',
            'chargeId' => 'ch1', 'traceId' => 'abc123']);

        try {
            $this->client->charges()->createWithToken(new NewChargeToken(amount: 10.0, token: 't1'));
            self::fail('no exception');
        } catch (ApiException $e) {
            self::assertSame(103, $e->errorCode);
            self::assertSame(103, $e->getCode());
            self::assertFalse($e->isRetryable);
            self::assertSame('https://docs.payfurl.com/errorcodes#103', $e->type);
            self::assertSame('/charge/token', $e->resource);
            self::assertSame(['Card' => 'declined'], $e->details);
            self::assertSame('05', $e->gatewayCode);
            self::assertSame('Do not honour', $e->gatewayMessage);
            self::assertSame('ch1', $e->chargeId);
            self::assertSame('abc123', $e->traceId);
        }
    }

    public function testGetIsRetriedAfterNetworkErrors(): void
    {
        $this->transport->queueFailure()->queueFailure(timedOut: true)->queue(200, ['chargeId' => 'ch1']);

        self::assertSame('ch1', $this->client->charges()->get('ch1')->chargeId);
        self::assertCount(3, $this->transport->requests);
    }

    public function testGetGivesUpAfterMaxRetries(): void
    {
        $this->transport->queueFailure()->queueFailure()->queueFailure(timedOut: true);

        try {
            $this->client->charges()->get('ch1');
            self::fail('no exception');
        } catch (NetworkException $e) {
            self::assertTrue($e->timedOut);
            self::assertTrue($e->isRetryable);
            self::assertSame(0, $e->httpCode);
        }
        self::assertCount(3, $this->transport->requests);
    }

    public function testGetIsRetriedAfterRetryableApiErrors(): void
    {
        $this->transport->queue(503, ['message' => 'Unavailable'])->queue(400, ['message' => 'Busy', 'isRetryable' => true])->queue(200, ['chargeId' => 'ch1']);

        self::assertSame('ch1', $this->client->charges()->get('ch1')->chargeId);
        self::assertCount(3, $this->transport->requests);
    }

    public function testGetIsNotRetriedAfterOtherErrors(): void
    {
        $this->transport->queue(404, ['message' => 'Not found']);

        $this->expectException(NotFoundException::class);
        try {
            $this->client->charges()->get('ch1');
        } finally {
            self::assertCount(1, $this->transport->requests);
        }
    }

    public function testChargeIsNeverRetried(): void
    {
        $this->transport->queueFailure(timedOut: true);

        $this->expectException(NetworkException::class);
        try {
            $this->client->charges()->createWithToken(new NewChargeToken(amount: 10.0, token: 't1'));
        } finally {
            self::assertCount(1, $this->transport->requests);
        }
    }

    public function testRetryableChargeErrorIsNotRetried(): void
    {
        $this->transport->queue(500, ['message' => 'Gateway timeout', 'isRetryable' => true]);

        try {
            $this->client->charges()->createWithToken(new NewChargeToken(amount: 10.0, token: 't1'));
            self::fail('no exception');
        } catch (ApiException $e) {
            self::assertTrue($e->isRetryable);
        }
        self::assertCount(1, $this->transport->requests);
    }

    public function testEmptyIdIsRejectedBeforeSending(): void
    {
        try {
            $this->client->charges()->get('');
            self::fail('no exception');
        } catch (InvalidRequestException $e) {
            self::assertSame('chargeId is empty', $e->getMessage());
            self::assertSame(0, $e->httpCode);
        }
        self::assertSame([], $this->transport->requests);
    }

    public function testSuccessWithABodyThatIsNotJson(): void
    {
        $this->transport->queue(200, 'ok');

        $this->expectException(ApiException::class);
        $this->client->charges()->get('ch1');
    }

    public function testErrorCodeNamesTheCode(): void
    {
        self::assertSame(ErrorCode::ValidationError, ErrorCode::tryFrom(90));
        self::assertSame('https://docs.payfurl.com/errorcodes#90', ErrorCode::ValidationError->url());
        self::assertNull(ErrorCode::tryFrom(random_int(100000, 200000)));
    }
}
