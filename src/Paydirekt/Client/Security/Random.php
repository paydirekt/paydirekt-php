<?php

namespace Paydirekt\Client\Security;

/**
 * Wrapper class to generate a string of pseudo-random bytes with desired length.
 */
final class Random
{
    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a pseudo-random string of bytes.
     * <p>
     * openssl_random_pseudo_bytes() does not always return a cryptographically strong result. See bug report
     * https://bugs.php.net/bug.php?id=70014 for further details. A fix is provided as of PHP 5.4.44, 5.5.28
     * and 5.6.12.
     *
     * Although generating cryptographically secure pseudo-random bytes via random_bytes() was added
     * to PHP in PHP 7.0, a userland polyfill implementation of random_bytes() and random_int() is available
     * for PHP 5.2 to 5.6, inclusive. See https://github.com/paragonie/random_compat for further details.
     *
     * @param int $length The length of the desired string of bytes. Must be a positive integer.
     *
     * @return string The string of bytes.
     */
    public static function createRandomPseudoBytes($length)
    {
        if ($length <= 0) {
            throw new \InvalidArgumentException("length is not a positive integer");
        }
        if (!function_exists("random_bytes")) {
            throw new \BadFunctionCallException("unable to call function random_bytes");
        }

        return random_bytes($length);
    }
}
