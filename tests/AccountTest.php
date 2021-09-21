<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../src/Config.php");
require_once(__DIR__ . "/../src/Account.php");
require_once(__DIR__ . "/TestBase.php");
require_once(__DIR__ . "/../src/ResponseException.php");

use payFURL\Sdk\Config;
use payFURL\Sdk\Account;
use payFURL\Sdk\ResponseException;

final class AccountTest extends TestBase
{
    public function testRegister(): void
    {
        $svc = new Account();

        $email = bin2hex(random_bytes(16)) . "@test.com";
        $result = $svc->Register(["EmailAddress" => $email, "Password" => "testPassword", "Timezones" => "Australia/Sydney"]);

        $this->assertSame($email, $result["emailAddress"]);
    }
}