<?php declare(strict_types=1);

require_once(__DIR__ . '/TestConfiguration.php');
require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Token.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\ResponseException;
use payFURL\Sdk\Token;

final class TokenTest extends TestBase
{
    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testSingle(): void
    {
        $svc = new Token();

        $tokenId = $this->getConfiguredToken();
        $result = $svc->Single($tokenId);

        $this->assertSame($tokenId, $result['tokenId']);
        $this->assertIsArray($result['provider']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testSearchAcceptsLowercaseKeys(): void
    {
        $svc = new Token();

        $result = $svc->Search([
            'providerId' => TestConfiguration::getProviderId(),
            'limit' => 1,
        ]);

        $this->assertSame(1, $result['limit']);
        $this->assertGreaterThan(0, $result['count']);
        $this->assertCount(1, $result['tokens']);
    }

    private function getConfiguredToken(): string
    {
        $config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
        $tokens = $config['Tokens'];

        return $tokens[count($tokens) - 1];
    }
}
