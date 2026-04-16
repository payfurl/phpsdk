<?php declare(strict_types=1);

require_once(__DIR__ . '/TestConfiguration.php');
require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Version.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\ResponseException;
use payFURL\Sdk\Version;

final class VersionTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testGet(): void
    {
        $svc = new Version();

        $result = $svc->Get();

        $this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $result['version']);
        $this->assertNotEmpty($result['released']);
        $this->assertNotEmpty($result['region']);
    }
}
