<?php declare(strict_types=1);

use payFURL\Sdk\HttpWrapper;

require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/tools/HttpWrapper.php");

final class HttpWrapperTest extends TestBase
{
    public function testMaskCardDataHidesCardNumberAndCcv(): void
    {
        $masked = HttpWrapper::MaskCardData('{"PaymentInformation":{"CardNumber":"4111111111111111","ExpiryDate":"12/30","Ccv":"123"},"vaultId":"v1","cardNumber": "5555555555554444","cvv":"999"}');

        $this->assertSame('{"PaymentInformation":{"CardNumber":"************1111","ExpiryDate":"12/30","Ccv":"***"},"vaultId":"v1","cardNumber": "************4444","cvv":"***"}', $masked);
    }

    public function testMaskCardDataLeavesOtherOutputAlone(): void
    {
        $this->assertSame('{"chargeId":"c1"}', HttpWrapper::MaskCardData('{"chargeId":"c1"}'));
        $this->assertFalse(HttpWrapper::MaskCardData(false));
    }
}
