<?php


namespace Paydirekt\Client\Rest;

/**
 * A builder for HTTP GET Requests.
 */
interface GetRequestBuilder
{
    /**
     * Use the given header in the GET request.
     * <p>
     * @param $header The header to use in request.
     * @return GetRequestBuilder this.
     */
    public function withHeader($header);

    /**
     * Use the standard headers in the GET request.
     * <p>
     * @param $accessToken The access token.
     * @return GetRequestBuilder this.
     */
    public function withStandardHeaders($accessToken);

    /**
     * Accept the given CA root certificates in communication with endpoint.
     * <p>
     * @param string $cafile The path to an file containing the accepted CA root certificates.
     * @return GetRequestBuilder this.
     */
    public function withCAFile($cafile);

    /**
     * Builds the GET request.
     * <p>
     * @return resource The GET request.
     */
    public function build();
}