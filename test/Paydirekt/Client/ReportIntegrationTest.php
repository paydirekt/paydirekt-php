<?php


namespace Paydirekt\Client;


use Paydirekt\Client\Report\ReportClient;
use Paydirekt\Client\TestUtil\TestDataProvider;
use Paydirekt\Client\Utility\CsvDocument;

class ReportIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $reportClient;
    private $accessToken;
    private $reportRequest;

    protected function setUp()
    {
        $testDataProvider = TestDataProvider::newTestDataProvider();
        $this->accessToken = $testDataProvider->getAccessToken();
        $this->reportClient = ReportClient::withStandardEndpoint();
        $this->reportRequest = array(
            'from' => '2016-09-01T12:00:00Z',
            'to' => '2016-10-05T12:00:00Z',
            'checkoutInvoiceNumbers' => '20150112334345'
        );
    }

    public function testThatTransactionsReportCanBeRetrievedAsJson()
    {
        $transactions = $this->reportClient->getTransactionsAsJson($this->reportRequest, $this->accessToken);

        $this->assertNotNull($transactions);
        $this->assertTrue(is_array($transactions));
        $this->assertArrayHasKey('transactions', $transactions);
        $this->assertTrue(is_array($transactions['transactions']));

        foreach ($transactions['transactions'] as $transaction)
        {
            $this->assertDateIsBetweenRange($transaction['transactionDate'],
                $this->reportRequest['from'],
                $this->reportRequest['to']
            );
        }
    }

    public function testThatTransactionsReportCanBeRetrievedAsCsv()
    {
        $transactions = $this->reportClient->getTransactionsAsCsv($this->reportRequest, $this->accessToken);

        $this->assertNotNull($transactions);

        $csvDocument = new CsvDocument($transactions);
        $this->assertContains('transactionDate', $csvDocument->getHeader());
        foreach ($csvDocument->getData() as $transaction)
        {
            $this->assertEquals(count($csvDocument->getHeader()), count($transaction));
            $this->assertDateIsBetweenRange($transaction['transactionDate'],
                $this->reportRequest['from'],
                $this->reportRequest['to']
            );
        }
    }

    private function assertDateIsBetweenRange($date, $beginRange, $endRange)
    {
        $date = date_parse($date);
        $beginRange = date_parse($beginRange);
        $endRange = date_parse($endRange);
        $this->assertTrue($date >= $beginRange);
        $this->assertTrue($date <= $endRange);
    }
}