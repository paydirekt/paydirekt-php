<?php

namespace Paydirekt\Client\Security;

/*
 * Mock function checking the list of defined functions, to allow for
 * tests concerning exceptions due to undefined functions.
 */
function function_exists($function_name) {
    global $mock_function_exists;
    if ($mock_function_exists) {
        return false;
    } else {
        return call_user_func_array('\function_exists', func_get_args());
    }
}

class RandomTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resetFunctionExistsMock();
    }

    public function tearDown()
    {
        $this->resetFunctionExistsMock();
    }

    public function testThatRandomPseudoBytesAreGeneratedWithCorrectSize()
    {
        for ($length = 1; $length < 100; $length++){
            $random = Random::createRandomPseudoBytes($length);
            $this->assertEquals(strlen($random), $length);
        }
    }

    public function testThatDifferentRandomPseudoBytesAreGenerated()
    {
        $random1 = Random::createRandomPseudoBytes(64);
        $random2 = Random::createRandomPseudoBytes(64);

        $this->assertTrue($random1 != $random2);
    }

    public function testThatInvalidArgumentExceptionForWrongLengthIsRaised()
    {
        $this->setExpectedException("InvalidArgumentException");
        Random::createRandomPseudoBytes(-1);
    }

    public function testThatInvalidArgumentExceptionForWrongInputTypeIsRaised()
    {
        $this->setExpectedException("InvalidArgumentException");
        Random::createRandomPseudoBytes("abc");
    }

    public function testThatRuntimeExceptionForUndefinedFunctionsIsRaised()
    {
        global $mock_function_exists;
        $mock_function_exists = true;

        $this->setExpectedException("BadFunctionCallException");
        Random::createRandomPseudoBytes(32);
    }

    private function resetFunctionExistsMock() {
        global $mock_function_exists;
        $mock_function_exists = false;
    }
}
