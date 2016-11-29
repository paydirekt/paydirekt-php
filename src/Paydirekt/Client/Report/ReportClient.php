<?php


namespace Paydirekt\Client\Report;


use Paydirekt\Client\Rest\EndpointConfiguration;
use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\RequestExecutor;

/**
 * This client connects to the reports endpoint.
 * Merchants can obtain reports about the transaction conducted for their webshops.
 */
class ReportClient
{
    private $reportEndpoint;

    /**
     * ReportClient constructor.
     * <p>
     * @param $reportEndpoint The endpoint to retrieve reports.
     */
    public function __construct($reportEndpoint)
    {
        $this->reportEndpoint = $reportEndpoint;
    }

    /**
     * Creates a report client for the standard report endpoint.
     * <p>
     * @return ReportClient The new ReportClient.
     */
    public static function withStandardEndpoint()
    {
        return new self(EndpointConfiguration::getTransactionReportsEndpoint());
    }

    private function getTransactions($reportRequest, $header, $deserialize)
    {
        $url = $this->reportEndpoint.'?'.http_build_query($reportRequest);
        $request = RequestBuilderFactory::newGetRequestBuilder($url)
            ->withHeader($header)
            ->build();
        return RequestExecutor::executeRequest($request, $deserialize);
    }

    /**
     * Retrieves a transactions report in JSON format.
     * <p>
     * @param array $reportRequest The description of the report to retrieve.
     * @param string $accessToken The merchant access token.
     * @return array The retrieved transactions report.
     */
    public function getTransactionsAsJson($reportRequest, $accessToken)
    {
        $header = array(
            "Accept: application/json",
            "Authorization: Bearer " . $accessToken
        );
        return $this->getTransactions($reportRequest, $header, true);
    }

    /**
     * Retrieves a transactions report in CSV format.
     * <p>
     * @param array $reportRequest The description of the report to retrieve.
     * @param string $accessToken The merchant access token.
     * @return string The retrieved transactions report.
     */
    public function getTransactionsAsCSV($reportRequest, $accessToken)
    {
        $header = array(
            "Accept: text/csv",
            "Authorization: Bearer " . $accessToken
        );
        return $this->getTransactions($reportRequest, $header, false);
    }
}