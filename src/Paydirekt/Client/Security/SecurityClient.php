<?php

namespace Paydirekt\Client\Security;

use Paydirekt\Client\Rest\PostRequestBuilder;
use Paydirekt\Client\Rest\RequestBuilderFactory;
use Paydirekt\Client\Rest\RequestExecutor;

/**
 * This client connects to the security endpoint.
 * This endpoint authenticates a shop.
 */
class SecurityClient
{
    private $tokenEndpoint;
    private $apiKey;
    private $apiSecret;
    private $caFile;

    /**
     * Constructor.
     *
     * @param string tokenEndpoint The URL of the token obtain endpoint.
     * @param string apiKey The api key of the merchant.
     * @param string apiSecret The api secret of the merchant.
     * @param string caFile The root certificates to accept.
     */
    public function __construct($tokenEndpoint, $apiKey, $apiSecret, $caFile) {
        $this->tokenEndpoint = $tokenEndpoint;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->caFile = $caFile;
    }

    private function createPostRequest()
    {
        $requestId = UUID::createRandomUUID();
        $randomNonce = Nonce::createRandomNonce();

        $now = new \DateTime("now", new \DateTimeZone('UTC'));
        $timestamp = $now->format('YmdHis');

        $signature = Hmac::signature($requestId, $timestamp, $this->apiKey, $this->apiSecret, $randomNonce);

        $header = array();
        array_push($header, "X-Date: " . $now->format(DATE_RFC1123));
        array_push($header, "X-Request-ID: " . $requestId);
        array_push($header, "X-Auth-Key: " . $this->apiKey);
        array_push($header, "X-Auth-Code: " . $signature);
        array_push($header, "Content-Type: application/hal+json;charset=utf-8");
        array_push($header, "Accept: application/hal+json");

        $payload = json_encode(array(
            'grantType' => 'api_key',
            'randomNonce' => $randomNonce
        ));

        $requestBuilder = RequestBuilderFactory::newPostRequestBuilder($this->tokenEndpoint);
        return $requestBuilder->withEntity($payload)
            ->withHeader($header)
            ->withCAFile($this->caFile)
            ->build();
    }

    /**
     * Retrieves an OAuth2 Access Token that authenticates a shop.
     * The token must be provided in order to execute any requests against the merchant API.
     *
     * @return array The retrieved accessToken.
     */
    public function getAccessToken() {
        $request = $this->createPostRequest();
        return RequestExecutor::executeRequest($request, true);
    }

}