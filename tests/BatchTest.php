<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/TestConfiguration.php');
require_once(__DIR__ . '/../src/Config.php');
require_once(__DIR__ . '/../src/Charge.php');
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/ResponseException.php');

use payFURL\Sdk\Batch;
use payFURL\Sdk\ResponseException;

final class BatchTest extends TestBase
{
    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testCreateTransactionWithPaymentMethod(): void
    {
        $svc = new Batch();

        $result = $svc->CreateTransactionWithPaymentMethod($this->getNewTransactionPaymentMethod());

        $this->assertSame('RECEIVED', $result['status']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testGetBatch(): void
    {
        $svc = new Batch();

        $batch = $svc->CreateTransactionWithPaymentMethod($this->getNewTransactionPaymentMethod());
        $result = $svc->GetBatch(['BatchId' => $batch['batchId']]);

        $this->assertSame('RECEIVED', $result['status']);
        $this->assertSame('PaymentMethodId,Amount,Currency,Reference,Status,TransactionId\r\n', $result['results']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testGetBatchStatus(): void
    {
        $svc = new Batch();

        $batch = $svc->CreateTransactionWithPaymentMethod($this->getNewTransactionPaymentMethod());
        $result = $svc->GetBatchStatus(['BatchId' => $batch['batchId']]);

        $this->assertSame('RECEIVED', $result['status']);
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function testSearchBatch(): void
    {
        $svc = new Batch();

        $description = bin2hex(random_bytes(16));

        $svc->CreateTransactionWithPaymentMethod($this->getNewTransactionPaymentMethod($description));
        $result = $svc->Search(['Description' => $description]);

        $this->assertSame($description, $result['description']);
    }

    private function getNewTransactionPaymentMethod($description = "Test"): array
    {
        return [
            'Count' => 1,
            'Description' => $description,
            'Batch' => "PaymentMethodId,Amount,Currency,Reference\ntest,123.4,AUD,reference"
        ];
    }
}
