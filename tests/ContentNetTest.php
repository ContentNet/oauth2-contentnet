<?php

namespace ContentNet\OAuth2\Client\Test\Provider;

use ContentNet\OAuth2\Client\Provider\ContentNet as ContentNet;
use League\OAuth2\Client\Token\AccessToken as AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException as IdentityProviderException;

class ContentNetTest extends \PHPUnit_Framework_TestCase
{
    private $config = [
        'clientId'     => 'mock_client_id',
        'clientSecret' => 'mock_secret',
        'redirectUri'  => 'none',
    ];

    private $sandboxConfig = [
        'clientId'     => 'mock_client_id',
        'clientSecret' => 'mock_secret',
        'redirectUri'  => 'none',
        'serverDomain' => 'https://api-devex.contentnet.com',
    ];

    /**
     * @dataProvider dpConfigs
     */
    public function testGetBaseAuthorizationUrl($config, $results)
    {
        $provider = new ContentNet($config);
        $this->assertEquals($results['authurl'], $provider->getBaseAuthorizationUrl());
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testGetBaseAccessTokenUrl($config, $results)
    {
        $provider = new ContentNet($config);
        $this->assertEquals($results['tokenurl'], $provider->getBaseAccessTokenUrl([]));
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testGetResourceOwnerDetailsUrl($config, $results)
    {
        $provider = new ContentNet($config);
        $token = new AccessToken(['access_token' => 'abc']);
        $this->assertEquals($results['userurl'] . 'abc', $provider->getResourceOwnerDetailsUrl($token));
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testCreateResourceOwner($config, $results)
    {
        $provider = new ContentNet($config);
        $token = new AccessToken(['access_token' => 'abc']);
        $response = [
            'user_id' => 1,
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
        ];

        $user = $provider->createResourceOwner($response, $token);
        $this->assertSame($user, $response);
    }

    public function testCheckResponse()
    {
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');
        $response->method('getStatusCode')
                 ->will($this->returnValue(999));

        $provider = new ContentNet($this->config);

        $provider->checkResponse($response, []);

        $this->setExpectedException(IdentityProviderException::class);
        $provider->checkResponse($response, ['error' => 'foo']);
    }

    /**
     * @covers ContentNet\OAuth2\Client\Provider\ContentNet::getDefaultScopes
     * @covers ContentNet\OAuth2\Client\Provider\ContentNet::getBaseAuthorizationUrl
     * @dataProvider dpConfigs
     */
    public function testGetAuthorizationUrl($config, $results)
    {
        $provider = new ContentNet($config);
        $url = $provider->getAuthorizationUrl();
        $baseUrl = $provider->getBaseAuthorizationUrl();
        $this->assertRegexp("#\Q$baseUrl\E.*&scope=charge#", $url);
    }

    public function dpConfigs()
    {
        return [
            [
                $this->config,
                [
                    'authurl'  => 'https://api.contentnet.com/oauth?response_type=code',
                    'tokenurl' => 'https://api.contentnet.com/oauth',
                    'userurl'  => 'https://api.contentnet.com/api/v1/user/get?access_token=',
                ]
            ],
            [
                $this->sandboxConfig,
                [
                    'authurl'  => 'https://api-devex.contentnet.com/oauth?response_type=code',
                    'tokenurl' => 'https://api-devex.contentnet.com/oauth',
                    'userurl'  => 'https://api-devex.contentnet.com/api/v1/user/get?access_token=',
                ]
            ],
        ];
    }
}
