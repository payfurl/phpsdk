<?php

use PHPUnit\Framework\TestCase;
use payFURL\Sdk\PaymentLink;
use payFURL\Sdk\ResponseException;

final class PaymentLinkTest extends TestBase
{
    private PaymentLink $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentLink();
    }

    private function generateCreatePaymentLink(): array
    {
        return [
            'Title' => 'Test Payment Link',
            'Amount' => 1000,
            'Currency' => 'USD',
        ];
    }

    /**
     * @throws ResponseException
     */
    public function testCreatePaymentLink(): void
    {
        $result = $this->service->Create($this->generateCreatePaymentLink());
        $this->assertNotNull($result);
        $this->assertArrayHasKey('paymentLinkId', $result);
    }

    /**
     * @throws ResponseException
     */
    public function testGetPaymentLink(): void
    {
        $result = $this->service->Create($this->generateCreatePaymentLink());
        $paymentLink = $this->service->Single($result['paymentLinkId']);
        $this->assertNotNull($paymentLink);
        $this->assertSame($result['paymentLinkId'], $paymentLink['paymentLinkId']);
    }

    /**
     * @throws ResponseException
     */
    public function testSearchPaymentLink(): void
    {
        $result = $this->service->Create($this->generateCreatePaymentLink());
        $searchResult = $this->service->Search([]);
        $this->assertNotNull($searchResult);
        $this->assertArrayHasKey('paymentLinks', $searchResult);
        $this->assertNotEmpty($searchResult['paymentLinks']);
        $ids = array_column($searchResult['paymentLinks'], 'paymentLinkId');
        $this->assertContains($result['paymentLinkId'], $ids);
    }
}
