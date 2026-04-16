<?php declare(strict_types=1);

require_once(__DIR__ . '/TestConfiguration.php');
require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Settlement.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\ResponseException;
use payFURL\Sdk\Settlement;

final class SettlementTest extends TestBase
{
    /**
     * @throws ResponseException
     */
    public function testGetSettlements(): void
    {
        $svc = new Settlement();

        $result = $svc->GetSettlements([
            'StartDate' => date('Y-m-d', strtotime('-30 days')),
        ]);

        $this->assertIsArray($result);
    }
}
