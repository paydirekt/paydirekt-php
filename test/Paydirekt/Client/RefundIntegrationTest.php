<?php


namespace Paydirekt\Client;


use Paydirekt\Client\Refund\RefundClient;
use Paydirekt\Client\TestUtil\RequestMocks;
use Paydirekt\Client\TestUtil\TestDataProvider;

class RefundIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $testDataProvider;

    protected function setUp()
    {
        $this->testDataProvider = TestDataProvider::newTestDataProvider();
    }

    public function testThatRefundForDirectSaleCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::directSaleCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);

        $refundRequest = json_encode(RequestMocks::refundRequest());
        $refund = RefundClient::createRefund($refundRequest, $checkout, $this->testDataProvider->getAccessToken());

        $this->assertNotNull($refund);
        $this->assertCreatedRefundValid($refund, RequestMocks::refundRequest());
    }

    public function testThatRefundForOrderCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $captureRequest = json_encode(RequestMocks::captureRequest());
        $this->testDataProvider->createCapture($captureRequest, $checkout);
        $checkout = $this->testDataProvider->getCheckout($checkout['checkoutId']);

        $refundRequest = json_encode(RequestMocks::refundRequest());
        $refund = RefundClient::createRefund($refundRequest, $checkout, $this->testDataProvider->getAccessToken());

        $this->assertNotNull($refund);
        $this->assertCreatedRefundValid($refund, RequestMocks::refundRequest());
    }

    public function testThatRetrievedRefundEqualsCreatedRefund()
    {
        $checkoutRequest = json_encode(RequestMocks::directSaleCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $refundRequest = json_encode(RequestMocks::refundRequest());
        $accessToken = $this->testDataProvider->getAccessToken();
        $createdRefund = RefundClient::createRefund($refundRequest, $checkout, $accessToken);

        $refundLink = $createdRefund['_links']['self']['href'];
        $retrievedRefund = RefundClient::getRefund($refundLink, $accessToken);

        $this->assertEquals($createdRefund, $retrievedRefund);
    }

    public function testThatRefundingAmountCanBeTwiceTheCaptureAmount()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $checkout = $this->testDataProvider->getApprovedCheckout($checkoutRequest);
        $firstCaptureRequest = array('amount' => 18.53);
        $secondCaptureRequest = array('amount' => 25.99);
        $this->testDataProvider->createCapture(json_encode($firstCaptureRequest), $checkout);
        $this->testDataProvider->createCapture(json_encode($secondCaptureRequest), $checkout);
        $checkout = $this->testDataProvider->getCheckout($checkout['checkoutId']);

        $refundRequest = array('amount' => 89.04); // 2 * (18.53 + 25.99)
        $accessToken = $this->testDataProvider->getAccessToken();
        $createdRefund = RefundClient::createRefund(json_encode($refundRequest), $checkout, $accessToken);

        $this->assertNotNull($createdRefund);
    }

    private function assertCreatedRefundValid($refund, $refundRequest)
    {
        foreach ($refundRequest as $key => $value)
        {
            $this->assertEquals($value, $refund[$key]);
        }
    }
}