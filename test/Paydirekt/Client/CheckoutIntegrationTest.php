<?php


namespace Paydirekt\Client;

use Paydirekt\Client\Checkout\CheckoutClient;
use Paydirekt\Client\Rest\EndpointConfiguration;
use Paydirekt\Client\Security\SecurityClient;
use Paydirekt\Client\TestUtil\RequestMocks;
use Paydirekt\Client\TestUtil\TestCustomer;
use Paydirekt\Client\TestUtil\TestDataProvider;


class CheckoutIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $accessToken;
    private $checkoutClient;

    protected function setUp()
    {
        $securityClient = new SecurityClient(EndpointConfiguration::getTokenObtainEndpoint(),
            EndpointConfiguration::API_KEY,
            EndpointConfiguration::API_SECRET,
            EndpointConfiguration::getCaFile());
        $accessToken = $securityClient->getAccessToken();
        $this->accessToken = $accessToken['access_token'];

        $this->checkoutClient = CheckoutClient::withStandardEndpoint();
    }

    public function testThatMinimalCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::minimalCheckoutRequest());
        $checkout = $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);

        $this->assertCreatedCheckoutValid($checkout, RequestMocks::minimalCheckoutRequest());
    }

    public function testThatDirectSaleCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::directSaleCheckoutRequest());
        $checkout = $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);

        $this->assertCreatedCheckoutValid($checkout, RequestMocks::directSaleCheckoutRequest());
        $this->assertEquals('OPEN', $checkout['status']);
    }

    public function testThatExpressCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::expressCheckoutRequest());
        $checkout = $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);

        $this->assertCreatedCheckoutValid($checkout, RequestMocks::expressCheckoutRequest());
        $this->assertEquals('OPEN', $checkout['status']);
    }

    public function testThatOrderCheckoutCanBeCreated()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $checkout = $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);

        $this->assertCreatedCheckoutValid($checkout, RequestMocks::orderCheckoutRequest());
        $this->assertEquals('OPEN', $checkout['status']);
    }

    public function testThatOrderCheckoutWithoutCaptureCanBeClosed()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $approvedCheckout = TestDataProvider::newTestDataProvider()->getApprovedCheckout($checkoutRequest);

        $this->assertEquals('APPROVED', $approvedCheckout['status']);

        $closedCheckout = $this->checkoutClient->closeCheckout($approvedCheckout, $this->accessToken);

        $this->assertEquals('CLOSED', $closedCheckout['status']);
    }

    public function testThatOrderCheckoutWithCaptureCanBeClosed()
    {
        $checkoutRequest = json_encode(RequestMocks::orderCheckoutRequest());
        $testDataProvider = TestDataProvider::newTestDataProvider();
        $approvedCheckout = $testDataProvider->getApprovedCheckout($checkoutRequest);

        $this->assertEquals('APPROVED', $approvedCheckout['status']);

        $captureRequest = json_encode(RequestMocks::captureRequest());
        $testDataProvider->createCapture($captureRequest, $approvedCheckout);

        $closedCheckout = $this->checkoutClient->closeCheckout($approvedCheckout, $this->accessToken);

        $this->assertEquals('CLOSED', $closedCheckout['status']);
    }

    public function testThatRetrievedCheckoutEqualsCreatedCheckout()
    {
        $checkoutRequest = json_encode(RequestMocks::minimalCheckoutRequest());
        $createdCheckout = $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);
        $retrievedCheckout = $this->checkoutClient->getCheckout($createdCheckout['checkoutId'], $this->accessToken);

        $this->assertEquals($createdCheckout, $retrievedCheckout);
    }

    private function assertCreatedCheckoutValid($checkout, $checkoutRequest)
    {
        $this->assertNotNull($checkout['checkoutId']);
        // These items are not necessarily part of the checkout even though they their send with the checkout request
        $exclude = array('overcapture', 'sha256hashedEmailAddress');
        foreach ($checkoutRequest as $key => $value)
        {
            if (!in_array($key, $exclude))
            {
                $this->assertEquals($value, $checkout[$key]);
            }
        }
        $this->assertNotNull($checkout['_links']);
        $this->assertNotNull($checkout['_links']['self']);
        $this->assertNotNull($checkout['_links']['approve']);
    }
}