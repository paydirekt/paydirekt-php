<?php

namespace Paydirekt\Client\Security;

use Paydirekt\Client\Utility\Base64Url;

/**
 * HMAC: Keyed-Hashing for Message Authentication.
 * <p>
 * https://tools.ietf.org/html/rfc2104
 * <p>
 * paydirekt uses a HMAC-based signature to authenticate a client system of the REST API without transferring a secret
 * over the wire.
 * <p>
 * The signature is created on the client-side by applying HMAC to specific request information (string to sign) using
 * the confidential API secret as key.
 * <p>
 * The signature is verified on the server-side.
 */
final class Hmac
{
    const CRYPTO_ALGORITHM = "sha256";

    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Generate the HMAC signature. The strong SHA-256 algorithm is used.
     *
     * @param string $requestId The request ID as defined in the request header to identify the message.
     * @param string $timestamp The current timestamp to ensure that the signature cannot be used again.
     * @param string $apiKey The API key to identify the shop.
     * @param string $apiSecret The confidential API secret as provided with the API key.
     * @param string $randomNonce A random nonce as transferred in the request body to randomize the request. Use {@link Nonce#createRandomNonce()} to generate a random nonce.
     * @return string The HMAC signature to be used in the header field {@code X-Auth-Code} in the token obtain endpoint.
     */
    public static function signature($requestId, $timestamp, $apiKey, $apiSecret, $randomNonce)
    {
        self::validateApiSecret($apiSecret);

        $stringToSign = self::stringToSign($requestId, $timestamp, $apiKey, $randomNonce);
        $apiSecretDecoded = Base64Url::decode($apiSecret);

        if (!in_array(self::CRYPTO_ALGORITHM, hash_algos(), true)) {
            throw new \RuntimeException("Could not initialize hmac. " .self::CRYPTO_ALGORITHM ." is not supported.");
        }

        $hash = hash_hmac(self::CRYPTO_ALGORITHM, $stringToSign, $apiSecretDecoded, true);
        $signature = Base64Url::encode($hash);

        return $signature;
    }

    /**
     * Build the string to sign, which is required to generate the HMAC signature.
     *
     * @param string $requestId The request ID as defined in the request header to identify the message.
     * @param string $timestamp The current timestamp as string to ensure that the signature cannot be used again. Must be in format {@code yyyyMMddHHmmss} with Timezone GMT.
     * @param string $apiKey The API key to identify the shop.
     * @param string $randomNonce A random nonce as transferred in the request body to randomize the request. Use {@link Nonce#createRandomNonce()} to generate a random nonce.
     * @return string The string to sign, used as input in {@link #signature(String, String)}.
     */
    public static function stringToSign($requestId, $timestamp, $apiKey, $randomNonce)
    {
        self::validateRequestId($requestId);
        self::validateDateString($timestamp);
        self::validateApiKey($apiKey);
        self::validateNonce($randomNonce);

        $stringToSign = sprintf("%s:%s:%s:%s", $requestId, $timestamp, $apiKey, $randomNonce);

        return $stringToSign;
    }

    private static function validateNonce($randomNonce)
    {
        if ($randomNonce === NULL || !trim($randomNonce)) {
            throw new \InvalidArgumentException("randomNonce is not set");
        }
        if (strlen($randomNonce) < 10) {
            throw new \InvalidArgumentException("randomNonce is not greater or equal the minimum length (10): " .$randomNonce);
        }
        if (strlen($randomNonce) > 64) {
            throw new \InvalidArgumentException("randomNonce is not less or equal the maximum length (64): " .$randomNonce);
        }
        if (!Base64Url::isBase64UrlEncoded($randomNonce)) {
            throw new \InvalidArgumentException("randomNonce is not base 64 url encoded: " .$randomNonce);
        }
    }

    private static function validateApiKey($apiKey)
    {
        if ($apiKey === NULL || !trim($apiKey)) {
            throw new \InvalidArgumentException("apiKey is not set");
        }
        if (!UUID::isUUID($apiKey)) {
            throw new \InvalidArgumentException("apiKey is not a valid UUID: " .$apiKey);
        }
    }

    private static function validateApiSecret($apiSecret)
    {
        if ($apiSecret === NULL || !trim($apiSecret)) {
            throw new \InvalidArgumentException("apiSecret is not set");
        }
        if (!Base64Url::isBase64UrlEncoded($apiSecret)) {
            throw new \InvalidArgumentException("apiSecret is not base 64 url encoded");
        }
        if (strlen(Base64Url::decode($apiSecret)) < 32) {
            throw new \InvalidArgumentException("apiSecret is not greater or equal the minimum length (32): " .strlen(Base64Url::decode($apiSecret)));
        }
    }

    private static function validateRequestId($requestId)
    {
        if ($requestId === NULL || !trim($requestId)) {
            throw new \InvalidArgumentException("requestId is not set");
        }
        if (!UUID::isUUID($requestId)) {
            throw new \InvalidArgumentException("requestId is not a valid UUID: " .$requestId);
        }
    }

    private static function validateDateString($dateString)
    {
        if ($dateString === NULL || !trim($dateString)) {
            throw new \InvalidArgumentException("dateString is not set");
        }

        $ts = \DateTime::createFromFormat('YmdHis', $dateString, new \DateTimeZone('UTC'));
        if (strlen($dateString) != 14 || ($ts && $ts->format('YmdHis') != $dateString)) {
            throw new \InvalidArgumentException("dateString is not a valid timestamp in format yyyyMMddHHmmss: " .$dateString);
        }
    }
}
