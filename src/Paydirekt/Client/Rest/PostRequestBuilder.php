<?php


namespace Paydirekt\Client\Rest;

/**
 * A builder for HTTP POST requests.
 */
interface PostRequestBuilder
{

    /**
     * Use the given header in the POST request.
     * <p>
     * @param $header The header to use in request.
     * @return PostRequestBuilder this.
     */
    public function withHeader($header);

    /**
     * Use the standard headers in the POST request.
     * <p>
     * @param $accessToken The access token.
     * @return PostRequestBuilder this.
     */
    public function withStandardHeaders($accessToken);

    /**
     * Accept the given CA root certificates in communication with endpoint.
     * <p>
     * @param string $cafile The path to an file containing the accepted CA root certificates.
     * @return PostRequestBuilder this.
     */
    public function withCAFile($cafile);

    /**
     * Use the given payload in the POST request.
     * <p>
     * @param $payload The request body.
     * @return PostRequestBuilder this.
     */
    public function withEntity($payload);

    /**
     * Builds the POST request.
     * <p>
     * @return resource The POST request.
     */
    public function build();

}