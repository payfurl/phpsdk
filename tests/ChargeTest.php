<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Charge.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\Config;
use payFURL\Sdk\Charge;
use payFURL\Sdk\ResponseException;

final class ChargeTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testChargeWithCard(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCard([
                                           'Amount' => 15.5,
                                           'Currency' => 'AUD',
                                           'Reference' => '123',
                                           'ProviderId' => $this->CardProviderId,
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder'
                                           ]]);

        $this->assertSame('SUCCESS', $result['status']);
    }

    /**
     * @throws ResponseException
     */
    public function testCreateWithCardLeastCost(): void
    {
        $svc = new Charge();

        $result = $svc->CreateWithCardLeastCost([
                                                    'Amount' => 15.5,
                                                    'Currency' => 'AUD',
                                                    'Reference' => '123',
                                                    'PaymentInformation' => [
                                                        'CardNumber' => '4111111111111111',
                                                        'ExpiryDate' => '10/30',
                                                        'Ccv' => '123',
                                                        'Cardholder' => 'Test Cardholder']]);

        $this->assertSame('SUCCESS', $result['status']);
    }

    /**
     * @throws ResponseException
     */
    public function testWithInvalidProvider(): void
    {
        $svc = new Charge();

        $this->expectException(ResponseException::class);

        $result = $svc->CreateWithCard([
                                           'Amount' => 15.5,
                                           'Currency' => 'AUD',
                                           'Reference' => '123',
                                           'ProviderId' => 'invalid_provider',
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder']]);
    }

    /**
     * @throws ResponseException
     */
    public function testWithShortTimeout(): void
    {
        $svc = new Charge();
        $this->expectException(ResponseException::class);
        $this->expectExceptionCode(408);

        $Timeout = Config::$TimeoutMilliseconds = 10;

        Config::$TimeoutMilliseconds = $Timeout;

        $result = $svc->CreateWithCard([
                                           'Amount' => 15.5,
                                           'Currency' => 'AUD',
                                           'Reference' => '123',
                                           'ProviderId' => $this->CardProviderId,
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder']]);
    }

    /**
     * @throws ResponseException
     */
    public function testSingle(): void
    {
        $svc = new Charge();

        $chargeResult = $svc->CreateWithCard([
                                                 'Amount' => 15.5,
                                                 'Currency' => 'AUD',
                                                 'Reference' => '123',
                                                 'ProviderId' => $this->CardProviderId,
                                                 'PaymentInformation' => [
                                                     'CardNumber' => '4111111111111111',
                                                     'ExpiryDate' => '10/30',
                                                     'Ccv' => '123',
                                                     'Cardholder' => 'Test Cardholder']]);

        $singleResult = $svc->Single(['ChargeId' => $chargeResult['chargeId']]);

        $this->assertSame($chargeResult['chargeId'], $singleResult['chargeId']);
    }

    /**
     * @throws ResponseException
     */
    public function testRefund(): void
    {
        $svc = new Charge();

        $chargeResult = $svc->CreateWithCard([
                                                 'Amount' => 15.5,
                                                 'Currency' => 'AUD',
                                                 'Reference' => '123',
                                                 'ProviderId' => $this->CardProviderId,
                                                 'PaymentInformation' => [
                                                     'CardNumber' => '4111111111111111',
                                                     'ExpiryDate' => '10/30',
                                                     'Ccv' => '123',
                                                     'Cardholder' => 'Test Cardholder']]);

        $refundResult = $svc->Refund(['ChargeId' => $chargeResult['chargeId'], 'Amount' => 5]);

        $this->assertSame(5, $refundResult['refundedAmount']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testSearch(): void
    {
        $svc = new Charge();

        $Reference = bin2hex(random_bytes(16));
        $result = $svc->CreateWithCard([
                                           'Amount' => 15.5,
                                           'Currency' => 'AUD',
                                           'Reference' => $Reference,
                                           'ProviderId' => $this->CardProviderId,
                                           'PaymentInformation' => [
                                               'CardNumber' => '4111111111111111',
                                               'ExpiryDate' => '10/30',
                                               'Ccv' => '123',
                                               'Cardholder' => 'Test Cardholder']]);

        $searchResult = $svc->Search(array('Reference' => $Reference));

        $this->assertSame(1, $searchResult['count']);
    }

    /**
     * @throws ResponseException
     */
    public function testInvalidParameters(): void
    {
        $svc = new Charge();
        $this->expectException(ResponseException::class);
        $this->expectExceptionCode('90');

        $result = $svc->CreateWithCard([
            'Amount' => 15.5,
            'Currency' => 'AUD',
            'ProviderId' => $this->CardProviderId,
            'PaymentInformation' => [
                'CardNumber' => '4111111111111111',
                'ExpiryDate' => '10/30',
                'Ccv' => 'abcda',
                'Cardholder' => 'Test Cardholder']]);
    }
}
