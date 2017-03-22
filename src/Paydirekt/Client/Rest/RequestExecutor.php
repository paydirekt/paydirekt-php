<?php

namespace Paydirekt\Client\Rest;

/**
 * Executes REST requests.
 */
final class RequestExecutor
{
    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Sends a request to its REST endpoint and waits for its response.
     * <p>
     * @param string $request The request to send.
     * @param bool $deserialize Is the expected response a json object and should be deserialized?
     * @return array|string The response (type depends on $deserialize)
     */
    public static function executeRequest($request, $deserialize) {
        $response = curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

        if (!self::isResponseCodeSuccessful($responseCode))
        {
            //TODO: implement correct error handling procedure
            throw new \RuntimeException("Unexpected status code: ".$responseCode.PHP_EOL.
                    "Response: ".$response.PHP_EOL.
                    "curl_error: ".curl_error($request).PHP_EOL.
                    "Request-URL: ".curl_getinfo($request, CURLINFO_EFFECTIVE_URL)
            );
        }

        curl_close($request);

        if ($deserialize)
        {
            return json_decode($response, true);
        }
        
        return $response;
    }
    
    private static function isResponseCodeSuccessful($returnCode)
    {
        return $returnCode >= 200 && $returnCode < 300;
    }
}
