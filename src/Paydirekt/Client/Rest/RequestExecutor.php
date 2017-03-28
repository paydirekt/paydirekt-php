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
            $message = ($responseCode > 0 ? "Unexpected status code " .$responseCode .": " .$response : "");
            $message .= (curl_error($request) ? curl_error($request) : "");
            $message .= " Request-URL: ".curl_getinfo($request, CURLINFO_EFFECTIVE_URL);

            throw new \RuntimeException($message);
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
