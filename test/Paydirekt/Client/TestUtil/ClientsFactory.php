<?php


namespace Paydirekt\Client\TestUtil;


use Paydirekt\Client\Checkout\CheckoutClient;
use Paydirekt\Client\Rest\EndpointConfiguration;
use Paydirekt\Client\Rest\RequestExecutor;
use Paydirekt\Client\Security\SecurityClient;

/**
 * A factory for different types of clients.
 * All these clients only connect to the Paydirekt Sandbox.
 */
class ClientsFactory
{

    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a new SecurityClient.
     * <p>
     * @return SecurityClient The new SecurityClient.
     */
    public static function newSecurityClient() {
        return new SecurityClient(EndpointConfiguration::SANDBOX_TOKEN_OBTAIN_ENDPOINT,
            EndpointConfiguration::API_KEY, EndpointConfiguration::API_SECRET, EndpointConfiguration::getCaFile());
    }

    /**
     * Creates a new CheckoutClient.
     * <p>
     * @return CheckoutClient The new CheckoutClient.
     */
    public static function newCheckoutClient() {
        return new CheckoutClient(EndpointConfiguration::SANDBOX_CHECKOUT_ENDPOINT);
    }

}