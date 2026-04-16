<?php declare(strict_types=1);

require_once(__DIR__ . '/TestConfiguration.php');
require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Root.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\ResponseException;
use payFURL\Sdk\Root;

final class RootTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testGet(): void
    {
        $svc = new Root();

        $result = $svc->Get();

        $this->assertSame('ok', $result['status']);
    }
}
