<?php

namespace Paydirekt\Client;


use Paydirekt\Client\Rest\EndpointConfiguration;
use Paydirekt\Client\Security\SecurityClient;

class ObtainTokenIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatOAuth2TokenIsObtained()
    {
        $securityClient = new SecurityClient(EndpointConfiguration::getTokenObtainEndpoint(),
            EndpointConfiguration::API_KEY,
            EndpointConfiguration::API_SECRET,
            EndpointConfiguration::getCaFile());
        $accessToken = $securityClient->getAccessToken();

        $this->assertNotNull($accessToken['access_token']);
        $this->assertNotNull($accessToken['expires_in']);
        $this->assertGreaterThan(60, $accessToken['expires_in']);
    }
}
