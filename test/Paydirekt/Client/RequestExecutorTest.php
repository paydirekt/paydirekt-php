<?php

namespace Paydirekt\Client\Rest;

class RequestExecutorTest extends \PHPUnit_Framework_TestCase
{
    public function testThatExecuteRequestIsInvalidWhenResponseCodeIsNotSuccessful()
    {
        $this->setExpectedException("RuntimeException");
        RequestExecutor::executeRequest(curl_init(), false);
    }
}