<?php

namespace Paydirekt\Client\TestUtil;

/**
 * Endpoints and credentials for testing.
 */
final class TestProperties
{
    const CHECKOUT_CONFIRM_ENDPOINT = 'https://api.sandbox.paydirekt.de/api/checkout/v1/checkouts/{checkoutId}/confirm';
    const USER_TOKEN_OBTAIN_ENDPOINT = 'https://api.sandbox.paydirekt.de/api/accountuser/v1/token/obtain';

    const TEST_USER_NAME = 'github_testuser';
    const TEST_USER_HASHED_PW = 'PHHrLCitIr3f7m7PFZbdxtmvirHhbJtch_XqUaSTXRrXWdIYBjfgpRA2ICM6qY7s';
    const TEST_USER_BASIC_AUTH_HEADER = 'Basic YnYtY2hlY2tvdXQtd2ViOjhjZEtIZVJ3eDNVNHI3WTlvQ0JkZ1A1OW5DdmNHTWRMa0NmQVNXdVZDdm8=';
}