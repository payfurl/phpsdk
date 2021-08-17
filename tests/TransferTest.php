<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");
require_once(__DIR__ . "/../src/Charge.php");
require_once(__DIR__ . "/../src/Transfer.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Config;
use payFURL\Sdk\Charge;
use payFURL\Sdk\Transfer;
use payFURL\Sdk\ResponseException;

final class TransferTest extends TestBase
{
    // Commented out while sorting out permissions
    // public function testCreate(): void
    // {
    //     $chargeSvc = new Charge();

    //     $chargeResult = $chargeSvc->CreateWithCard(15.5, "AUD", "123", $this->CardProviderId, "4111111111111111", "10/30", "123", "Test Cardholder");

    //     $transferSvc = new Transfer();

    //     $Reference = bin2hex(random_bytes(16));
    //     $transfers = array(array("account" => "test@test.com", "amount" => 5, "currency" => "AUD"));

    //     $transferResult = $transferSvc->Create($Reference, $this->CardProviderId, $chargeResult["chargeId"], $transfers);

    //     $this->assertIsString($transferResult[0]["checkoutId"]);
    // }
}