<?php

declare(strict_types=1);

namespace payFURL\Sdk\Tests\Unit;

use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\Environment;
use payFURL\Sdk\Http\FakeTransport;
use payFURL\Sdk\PayfurlClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    #[DataProvider('baseUrls')]
    public function testEnvironmentAndKeyRegionPickTheHost(string $key, Environment|string $environment, string $expected): void
    {
        $transport = (new FakeTransport())->queue(200, ['status' => 'ok']);

        (new PayfurlClient($key, $environment, new ClientOptions(transport: $transport)))->root();

        self::assertSame($expected . '/', $transport->lastRequest()->url);
    }

    /**
     * @return iterable<string, array{string, Environment|string, string}>
     */
    public static function baseUrls(): iterable
    {
        yield 'sandbox au' => ['sectest0a1b2c-au', Environment::Sandbox, 'https://sandbox-api-au.payfurl.com'];
        yield 'production au' => ['seclive0a1b2c-AU', Environment::Production, 'https://api-au.payfurl.com'];
        yield 'development au' => ['sectest0a1b2c-au', Environment::Development, 'https://develop-api-au.payfurl.com'];
        yield 'sandbox, a region without its own host' => ['sectest0a1b2c-us', Environment::Sandbox, 'https://sandbox-api.payfurl.com'];
        yield 'production, a key without a region' => ['seclive0a1b2c', Environment::Production, 'https://api.payfurl.com'];
        yield 'local ignores the region' => ['sectest0a1b2c-au', Environment::Local, 'https://localhost:5001'];
        yield 'environment as a string' => ['sectest0a1b2c-au', ' Sandbox ', 'https://sandbox-api-au.payfurl.com'];
    }

    public function testBaseUrlOptionWins(): void
    {
        $transport = (new FakeTransport())->queue(200, ['status' => 'ok']);

        (new PayfurlClient('sectest0-au', Environment::Production, new ClientOptions(baseUrl: 'https://api:5001/', transport: $transport)))->root();

        self::assertSame('https://api:5001/', $transport->lastRequest()->url);
    }

    public function testHeaders(): void
    {
        $transport = (new FakeTransport())->queue(200, ['status' => 'ok']);

        (new PayfurlClient('sectest0-au', Environment::Sandbox, new ClientOptions(timeoutMilliseconds: 1500, transport: $transport)))->root();

        $request = $transport->lastRequest();
        self::assertSame('sectest0-au', $request->headers['x-secretkey']);
        self::assertArrayNotHasKey('x-publickey', $request->headers);
        self::assertSame('payfurl-php/' . PayfurlClient::VERSION . ' PHP/' . PHP_VERSION, $request->headers['User-Agent']);
        self::assertSame('application/json', $request->headers['Accept']);
        self::assertArrayNotHasKey('sdk-version', $request->headers);
        self::assertSame(1500, $request->timeoutMilliseconds);
        self::assertTrue($request->verifyTls);
    }

    public function testPublicKeyIsSentAsThePublicKey(): void
    {
        $transport = (new FakeTransport())->queue(200, ['hasCardProviders' => true]);

        $providers = (new PayfurlClient('pubtest0-au', Environment::Sandbox, new ClientOptions(transport: $transport)))
            ->info->providers(browserSdkVersion: '4.11.0');

        self::assertTrue($providers->hasCardProviders);
        self::assertSame('pubtest0-au', $transport->lastRequest()->headers['x-publickey']);
        self::assertArrayNotHasKey('x-secretkey', $transport->lastRequest()->headers);
        self::assertSame('4.11.0', $transport->lastRequest()->headers['sdk-version']);
    }

    public function testLocalTurnsTlsVerificationOff(): void
    {
        $transport = (new FakeTransport())->queue(200, ['status' => 'ok']);

        (new PayfurlClient('sectest0-au', Environment::Local, new ClientOptions(transport: $transport)))->root();

        self::assertFalse($transport->lastRequest()->verifyTls);
    }

    public function testVersionMatchesTheChangelog(): void
    {
        $changelog = (string) file_get_contents(__DIR__ . '/../../CHANGELOG.md');

        self::assertStringContainsString('## [' . PayfurlClient::VERSION . ']', $changelog);
    }

    public function testEmptyKeyIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PayfurlClient(' ', Environment::Sandbox);
    }

    public function testUnknownEnvironmentIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown payFURL environment "staging": use local, development, sandbox, production');

        new PayfurlClient('sectest0-au', 'staging');
    }
}
