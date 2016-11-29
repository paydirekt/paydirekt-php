<?php


namespace Paydirekt\Client\Capture;
use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\RequestExecutor;

/**
 * This client connects to the capture endpoint.
 * Captures charge the account of the buyer with a given amount and provide a payment guarantee to the merchant.
 */
class CaptureClient
{

    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a new capture for a given checkout.
     * <p>
     * @param array $captureRequest The description of the new capture.
     * @param array $checkout The checkout for which the capture shall be created.
     * @param string $accessToken The merchant access token.
     * @return array The new capture.
     */
    public static function createCapture($captureRequest, $checkout, $accessToken)
    {
        $captureLink = self::getCaptureEndpoint($checkout);
        $request = RequestBuilderFactory::newPostRequestBuilder($captureLink)
            ->withEntity($captureRequest)
            ->withStandardHeaders($accessToken)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    /**
     * Retrieves an existing capture.
     * <p>
     * @param string $captureLink The endpoint to retrieve the capture.
     * @param string $accessToken The merchant access token.
     * @return array The retrieved capture.
     */
    public static function getCapture($captureLink, $accessToken)
    {
        $request = RequestBuilderFactory::newGetRequestBuilder($captureLink)
            ->withStandardHeaders($accessToken)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    private static function getCaptureEndpoint($checkout)
    {
        return $checkout['_links']['captures']['href'];
    }
}