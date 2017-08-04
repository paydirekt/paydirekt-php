<?php

namespace Paydirekt\Client\Security;

class HmacValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNonceIsValid()
    {
        $this->invokePrivateStaticHmacMethod("validateNonce", "Qg5f0Q3ly1Cwh5M9zcw57jwHI_HPoKbjdHLurXGpPg0yaz-C6OWPpwnYi22bnB6S");
    }

    public function testThatNonceIsInvalidWhenNull()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateNonce", null);
    }

    public function testThatNonceIsInvalidWhenEmpty()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateNonce", " ");
    }

    public function testThatNonceIsInvalidWhenWrongEncoding()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateNonce", "Qg5f0Q3ly1Cwh5M9z+cw57jwHIHPoKbjdHLurXGpPg/yazdC6OWPpwnYi22bnB6S");
    }

    public function testThatNonceIsInvalidWhenTooShort()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateNonce", "xxx");
    }

    public function testThatNonceIsInvalidWhenTooLong()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateNonce", "12345678901234567890123456789012345678901234567890123456789012345");
    }

    public function testThatApiKeyIsValid()
    {
        $this->invokePrivateStaticHmacMethod("validateApiKey", "4c15310a-7936-4a19-8d80-f2b7bd95dc9b");
    }

    public function testThatApiKeyIsInvalidWhenNull()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiKey", null);
    }

    public function testThatApiKeyIsInvalidWhenEmpty()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiKey", " ");
    }

    public function testThatApiKeyIsInvalidWhenNotUuid()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiKey", "4c15310a-936-4a19-8d80-f2b7bd95dc9b");
    }

    public function testThatApiSecretIsValid()
    {
        $this->invokePrivateStaticHmacMethod("validateApiSecret", "GJlN718sQxN1unxbLWHVlcf0FgXw2kMyfRwD0mgTRME=");
    }

    public function testThatApiSecretIsInvalidWhenNull()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiSecret", null);
    }

    public function testThatApiSecretIsInvalidWhenEmpty()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiSecret", " ");
    }

    public function testThatApiSecretIsInvalidWhenWrongEncoding()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiSecret", "GJlN718sQxN1unxb/WHVlcf0FgXw2kMyfRwD0mgTRME=");
    }

    public function testThatApiSecretIsInvalidWhenTooShort()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateApiSecret", "apiSecretShorterThan32Chars");
    }

    public function testThatRequestIdIsValid()
    {
        $this->invokePrivateStaticHmacMethod("validateRequestId", "ec85749b-aa36-412a-a397-2b40200c119c");
    }

    public function testThatRequestIdIsInvalidWhenNull()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateRequestId", null);
    }

    public function testThatRequestIdIsInvalidWhenEmpty()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateRequestId", " ");
    }

    public function testThatRequestIdIsInvalidWhenNotUuid()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateRequestId", "ec85749b-aa3-412a-a397-2b40200c119c");
    }

    public function testThatDateStringIdIsValid()
    {
        $this->invokePrivateStaticHmacMethod("validateDateString", "20141231235859");
    }

    public function testThatDateStringIsInvalidWhenNull()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateDateString", null);
    }

    public function testThatDateStringIsInvalidWhenEmpty()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateDateString", " ");
    }

    public function testThatDateStringIsInvalidWhenTooShort()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateDateString", "201423235859");
    }

    public function testThatDateStringIsInvalidWhenNotADate()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->invokePrivateStaticHmacMethod("validateDateString", "20141232235859");
    }

    /**
     * Call protected/private static method of a class.
     *
     * @param string $methodName Method name to call
     * @param string $parameter Parameter to pass into method.
     *
     * @return mixed Method return value.
     */
    public function invokePrivateStaticHmacMethod($methodName, $parameter)
    {
        $reflection = new \ReflectionClass("Paydirekt\Client\Security\Hmac");
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(null, array($parameter));
    }
}
