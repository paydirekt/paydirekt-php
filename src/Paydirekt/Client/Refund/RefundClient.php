<?php


namespace Paydirekt\Client\Refund;
use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\RequestExecutor;

/**
 * This client connects to the refund endpoint.
 * Refunds return already approved payments to the buyer.
 * They are always applied to a checkout as a whole and not to individual captures.
 */
class RefundClient
{

    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a new refund.
     * <p>
     * @param array $refundRequest The description of the new refund.
     * @param array $checkout The checkout to refund.
     * @param string $accessToken The merchant access token.
     * @return array The new refund.
     */
    public static function createRefund($refundRequest, $checkout, $accessToken)
    {
        $refundEndpoint = self::getRefundEndpoint($checkout);
        $request = RequestBuilderFactory::newPostRequestBuilder($refundEndpoint)
            ->withStandardHeaders($accessToken)
            ->withEntity($refundRequest)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    /**
     * Retrieves an existing refund.
     * <p>
     * @param string $refundLink The endpoint to retrieve the refund.
     * @param string $accessToken The merchant access token.
     * @return array The retrieved refund.
     */
    public static function getRefund($refundLink, $accessToken)
    {
        $request = RequestBuilderFactory::newGetRequestBuilder($refundLink)
            ->withStandardHeaders($accessToken)
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    private static function getRefundEndpoint($checkout)
    {
        return $checkout['_links']['refunds']['href'];
    }
}