<?php


namespace Paydirekt\Client\Checkout;


use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\GetRequestBuilder;
use Paydirekt\Client\Rest\PostRequestBuilder;
use Paydirekt\Client\Rest\RequestExecutor;
use Paydirekt\Client\Rest\EndpointConfiguration;

/**
 * This client connects to the checkout endpoint.
 * Checkouts represent the payment process.
 */
class CheckoutClient
{
    private $checkoutEndpoint;

    /**
     * CheckoutClient constructor.
     * <p>
     * @param $checkoutEndpoint The endpoint to retrieve checkouts.
     */
    public function __construct($checkoutEndpoint)
    {
        $this->checkoutEndpoint = $checkoutEndpoint;
    }

    /**
     * Creates a checkout client for the standard checkout endpoint.
     * <p>
     * @return CheckoutClient The new CheckoutClient.
     */
    public static function withStandardEndpoint()
    {
        return new self(EndpointConfiguration::getCheckoutEndpoint());
    }

    /**
     * Creates a new checkout.
     * <p>
     * @param array checkoutRequest The description of the new checkout.
     * @param string accessToken The merchant access token.
     * @return array The new checkout.
     */
    public function createCheckout($checkoutRequest, $accessToken)
    {
        $request = RequestBuilderFactory::newPostRequestBuilder($this->checkoutEndpoint)
            ->withEntity($checkoutRequest)
            ->withStandardHeaders($accessToken)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    /**
     * Retrieves an existing checkout.
     * <p>
     * @param int $checkoutId The checkout identifier.
     * @param string $accessToken The merchant access token.
     * @return array The retrieved checkout.
     */
    public function getCheckout($checkoutId, $accessToken)
    {
        $request = RequestBuilderFactory::newGetRequestBuilder($this->checkoutEndpoint.'/'.$checkoutId)
            ->withStandardHeaders($accessToken)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    /**
     * Closes a checkout.
     * A checkout should be closed if no more capture will be created.
     * <p>
     * @param array $checkout The checkout to close.
     * @param string $accessToken The merchant access token.
     * @return array The closed checkout.
     */
    public function closeCheckout($checkout, $accessToken)
    {
        $closeCheckoutUrl = self::getCloseEndpoint($checkout);
        $request = RequestBuilderFactory::newPostRequestBuilder($closeCheckoutUrl)
            ->withStandardHeaders($accessToken)
            ->withEntity('')
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    private static function getCloseEndpoint($checkout)
    {
        return $checkout['_links']['close']['href'];
    }
}