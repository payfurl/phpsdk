<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Config.php');

use payFURL\Sdk\Config;

class TestBase extends TestCase
{
    public string $CardProviderId = 'ec422274fe6d4a6e9f54157381603740';
    public string $PaypalProviderId = 'a9f965efaa914a098d1a3402881599fd';
    public string $TokenId = '7a486ffcfaa24d99ac562858f599168b';

    protected function setUp(): void
    {
        Config::initialise('secteste760dc4185ba394e6148e2612b644de493cd068aa6', 'LOCAL', 60000, false);
    }
}
