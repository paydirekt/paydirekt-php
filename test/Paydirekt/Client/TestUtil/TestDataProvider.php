<?php


namespace Paydirekt\Client\TestUtil;

use Paydirekt\Client\Capture\CaptureClient;
use Paydirekt\Client\Checkout\CheckoutClient;

/**
 * Facility class to provide data for testing purposes.
 */
class TestDataProvider
{
    private $checkoutClient;
    private $testCustomer;
    private $accessToken;

    /**
     * TestDataProvider constructor.
     * <p>
     * @param CheckoutClient $checkoutClient The client to provide checkouts.
     * @param string $accessToken The merchant access token.
     * @param TestCustomer $testCustomer The client to approve checkouts.
     */
    public function __construct($checkoutClient, $accessToken, $testCustomer)
    {
        $this->checkoutClient = $checkoutClient;
        $this->accessToken = $accessToken;
        $this->testCustomer = $testCustomer;
    }

    /**
     * Creates a TestDataProvider for the standard endpoints.
     * <p>
     * @return TestDataProvider The new TestDataProvider.
     */
    public static function newTestDataProvider()
    {
        return new self(ClientsFactory::newCheckoutClient(),
            ClientsFactory::newSecurityClient()->getAccessToken()['access_token'],
            TestCustomer::withStandardEndpoints());
    }

    /**
     * Creates a new checkout.
     * <p>
     * @param array $checkoutRequest The description of the new checkout.
     * @return array The new checkout.
     */
    public function createCheckout($checkoutRequest)
    {
        return $this->checkoutClient->createCheckout($checkoutRequest, $this->accessToken);
    }

    /**
     * Retrieves an existing checkout.
     * <p>
     * @param int $checkoutId The identifier of the checkout to retrieve.
     * @return array The retrieved checkout.
     */
    public function getCheckout($checkoutId)
    {
        return $this->checkoutClient->getCheckout($checkoutId, $this->accessToken);
    }

    /**
     * Creates a new capture for a given checkout.
     * <p>
     * @param array $captureRequest The description of the new capture.
     * @param array $checkout The checkout for which the capture shall be created.
     * @return array The new capture.
     */
    public function createCapture($captureRequest, $checkout)
    {
        return CaptureClient::createCapture($captureRequest, $checkout, $this->accessToken);
    }

    /**
     * Creates a checkout, which is already approved by a test customer.
     * <p>
     * @param $checkoutRequest The description of the new checkout.
     * @return array The approved checkout.
     */
    public function getApprovedCheckout($checkoutRequest)
    {
        $checkout = $this->createCheckout($checkoutRequest);
        $checkoutId = $checkout['checkoutId'];
        $this->testCustomer->approveCheckout($checkoutId);
        return $this->getCheckout($checkoutId);
    }

    /**
     * Returns a valid merchant access token.
     * <p>
     * @return string The merchant access token.
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}