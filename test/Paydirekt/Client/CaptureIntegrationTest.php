<?php


namespace Paydirekt\Client;


use Paydirekt\Client\Capture\CaptureClient;
use Paydirekt\Client\TestUtil\RequestMocks;
use Paydirekt\Client\TestUtil\TestDataProvider;

class CaptureIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $testDataProvider;

    protected function setUp()
    {
        $this->testDataProvider = TestDataProvider::newTestDataProvider();
    }

    public function testThatCaptureOnOrderCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $accessToken = $this->testDataProvider->getAccessToken();
        $captureRequest = json_encode(RequestMocks::captureRequest());
        $createdCapture = CaptureClient::createCapture($captureRequest, $checkout, $accessToken);

        $this->assertCreatedCaptureValid($createdCapture);
        $this->assertEquals('CAPTURE_ORDER', $createdCapture['type']);
        $this->assertFalse($createdCapture['finalCapture']);
        $this->assertCreatedCaptureCorrect($createdCapture, RequestMocks::captureRequest(),
            array('merchantCaptureReferenceNumber', 'merchantReconciliationReferenceNumber',
                'captureInvoiceReferenceNumber', 'callbackUrlStatusUpdates', 'deliveryInformation'));
    }

    public function testThatCaptureOnDirectSaleCheckoutIsAutomaticallyCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::directSaleCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $captureLink = $checkout['_embedded']['captures'][0]['_links']['self']['href'];
        $accessToken = $this->testDataProvider->getAccessToken();
        $createdCapture = CaptureClient::getCapture($captureLink, $accessToken);

        $this->assertCreatedCaptureValid($createdCapture);
        $this->assertEquals('CAPTURE_DIRECT_SALE', $createdCapture['type']);
        $this->assertEquals(RequestMocks::directSaleCheckoutRequest()['totalAmount'], $createdCapture['amount']);
        $this->assertEquals(RequestMocks::directSaleCheckoutRequest()['deliveryInformation'], $createdCapture['deliveryInformation']);
    }

    public function testThatRetrievedCaptureEqualsCreatedCapture()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $accessToken = $this->testDataProvider->getAccessToken();
        $captureRequest = json_encode(RequestMocks::captureRequest());
        $createdCapture = CaptureClient::createCapture($captureRequest, $checkout, $accessToken);
        $captureLink = $createdCapture['_links']['self']['href'];
        $retrievedCapture = CaptureClient::getCapture($captureLink, $accessToken);

        $this->assertEquals($createdCapture, $retrievedCapture);
    }

    private function assertCreatedCaptureValid($capture)
    {
        $this->assertNotNull($capture);
        $this->assertNotNull($capture['transactionId']);

        $this->assertNotNull($capture['_links']);
        $this->assertNotNull($capture['_links']['self']);
        $this->assertNotNull($capture['_links']['self']['href']);
        $this->assertEquals('SUCCESSFUL', $capture['status']);
        $this->assertNotNull($capture['amount']);
    }

    private function assertCreatedCaptureCorrect($capture, $request, $whitelist)
    {
        foreach ($whitelist as $key)
        {
            $this->assertEquals($request[$key], $capture[$key]);
        }
    }
}