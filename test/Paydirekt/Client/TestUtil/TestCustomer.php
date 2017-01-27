<?php


namespace Paydirekt\Client\TestUtil;

use Paydirekt\Client\Rest\EndpointConfiguration;
use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\PostRequestBuilder;
use Paydirekt\Client\Rest\RequestExecutor;

/**
 * Automates the actions of an Paydirekt customer for testing purposes.
 * The test customer always acts against sandbox REST endpoints.
 */
class TestCustomer
{
    private $tokenObtainEndpoint;
    private $checkoutConfirmEndpoint;

    /**
     * TestCustomer constructor.
     * <p>
     * @param string $tokenObtainEndpoint The endpoint to obtain access tokens.
     * @param $checkoutConfirmEndpoint The endpoint to confirm checkouts.
     */
    public function __construct($tokenObtainEndpoint, $checkoutConfirmEndpoint)
    {
        $this->tokenObtainEndpoint = $tokenObtainEndpoint;
        $this->checkoutConfirmEndpoint = $checkoutConfirmEndpoint;
    }

    /**
     * Creates a test customer for the standard endpoints.
     * <p>
     * @return TestCustomer The new TestCustomer.
     */
    public static function withStandardEndpoints()
    {
        return new self(TestProperties::USER_TOKEN_OBTAIN_ENDPOINT, TestProperties::CHECKOUT_CONFIRM_ENDPOINT);
    }

    /**
     * Approve an existing checkout.
     * <p>
     * @param int $checkoutId The identifier of the checkout to confirm.
     */
    public function approveCheckout($checkoutId)
    {
        $userToken = $this->getUserToken($checkoutId);
        $accessToken = $userToken['access_token'];
        $confirmUrl = str_replace('{checkoutId}', $checkoutId, $this->checkoutConfirmEndpoint);
        $this->getCheckout($checkoutId, $accessToken); //fraud protection requires GET before APPROVE checkout
        $requestBuilder = RequestBuilderFactory::newPostRequestBuilder($confirmUrl);
        $request = $requestBuilder->withStandardHeaders($accessToken)
            ->withCAFile(EndpointConfiguration::getCaFile())
            ->withEntity('')
            ->build();

        RequestExecutor::executeRequest($request, false);
    }

    private function getCheckout($checkoutId, $accessToken)
    {
        $requestBuilder = RequestBuilderFactory::newGetRequestBuilder(EndpointConfiguration::SANDBOX_CHECKOUT_ENDPOINT . '/' . $checkoutId);
        $request = $requestBuilder->withStandardHeaders($accessToken)
            ->withCAFile(EndpointConfiguration::getCaFile())
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

    private function getUserToken($checkoutId)
    {
        $requestBuilder = RequestBuilderFactory::newPostRequestBuilder($this->tokenObtainEndpoint);
        $request = $requestBuilder->withHeader(array(
            'Authorization: ' . TestProperties::TEST_USER_BASIC_AUTH_HEADER,
            'Content-Type: application/hal+json;charset=utf-8',
            'Accept: application/hal+json',
            'User-Agent: Mozilla/5.0'
        ))->withEntity(json_encode(array(
            'processId' => $checkoutId,
            'password' => TestProperties::TEST_USER_HASHED_PW,
            'username' => TestProperties::TEST_USER_NAME,
            'grantType' => 'password'
        )))->withCAFile(EndpointConfiguration::getCaFile())
            ->build();
        return RequestExecutor::executeRequest($request, true);
    }

}