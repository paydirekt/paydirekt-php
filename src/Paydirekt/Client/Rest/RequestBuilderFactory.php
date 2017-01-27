<?php


namespace Paydirekt\Client\Rest;

/**
 * A factory for HTTP request builders.
 */
class RequestBuilderFactory implements GetRequestBuilder, PostRequestBuilder
{
    private $request;

    /**
     * Private constructor.
     * Use the static new*RequestBuilder(...) methods instead.
     * <p>
     * @param string $url The endpoint to issue the request.
     * @param bool $isPost Flag for post requests.
     */
    private function __construct($url, $isPost)
    {
        $this->request = curl_init();
        curl_setopt($this->request, CURLOPT_URL, $url);
        curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->request, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->request, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($this->request, CURLOPT_CAINFO, EndpointConfiguration::getCaFile());
        if ($isPost)
        {
            curl_setopt($this->request, CURLOPT_POST, 1);
            //set default request body to empty string
            //not setting bodies for POST requests at all leads to failures with some curl versions
            $this->withEntity('');
        }
    }

    /**
     * Creates a new HTTP GET request builder.
     * <p>
     * @param string $url The endpoint to issue the request.
     * @return GetRequestBuilder The new HTTP GET request builder.
     */
    public static function newGetRequestBuilder($url)
    {
        return new self($url, false);
    }

    /**
     * Creates a new HTTP POST request builder.
     * <p>
     * @param string $url The endpoint to issue the request.
     * @return PostRequestBuilder The new HTTP POST request builder.
     */
    public static function newPostRequestBuilder($url)
    {
        return new self($url, true);
    }

    public function withHeader($header)
    {
        curl_setopt($this->request, CURLOPT_HTTPHEADER, $header);
        return $this;
    }

    public function withStandardHeaders($accessToken)
    {
        $header = array();
        array_push($header, "Content-Type: application/hal+json;charset=utf-8");
        array_push($header, "Accept: application/hal+json");
        array_push($header, "Authorization: Bearer " . $accessToken);
        array_push($header, "User-Agent: Mozilla/5.0");

        curl_setopt($this->request, CURLOPT_HTTPHEADER, $header);
        return $this;
    }

    public function withCAFile($cafile)
    {
        curl_setopt($this->request, CURLOPT_CAINFO, $cafile);
        return $this;
    }

    public function withEntity($payload)
    {
        curl_setopt($this->request, CURLOPT_POSTFIELDS, $payload);
        return $this;
    }

    public function build()
    {
        return $this->request;
    }
}