<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Config.php');

use payFURL\Sdk\Config;

class TestBase extends TestCase
{
    public string $CardProviderId = 'a5682a53b7f14af8ad4afe67f166333d';
    public string $PayToProviderId = 'e409b55ad27f4f8f80237a37f56901de';
    public string $PaypalProviderId = 'e2f2b4ddb5e8447287345c4d44ac54b4';
    public string $TokenId = '8c084aa548af4b8ebe101f54d7ab3a3f';

    protected function setUp(): void
    {
        Config::initialise('sectestab8c10faebf84918758da45df1530a590dc295513b', 'LOCAL', 60000, true);
    }
}
