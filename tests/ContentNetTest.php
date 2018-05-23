<?php

namespace ContentNet\OAuth2\Client\Test\Provider;

use ContentNet\OAuth2\Client\Provider\ContentNet as OauthProvider;
use League\OAuth2\Client\Token\AccessToken as AccessToken;

class AmazonTest extends \PHPUnit_Framework_TestCase
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
    public function testUrlAuthorize($config, $results)
    {
        $provider = new OauthProvider($config);
        $this->assertEquals($results['authurl'], $provider->urlAuthorize());
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testUrlAccessToken($config, $results)
    {
        $provider = new OauthProvider($config);
        $this->assertEquals($results['tokenurl'], $provider->urlAccessToken());
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testUrlUserDetails($config, $results)
    {
        $provider = new OauthProvider($config);
        $token = new AccessToken(['access_token' => 'abc']);
        $this->assertEquals($results['userurl'] . 'abc', $provider->urlUserDetails($token));
    }

    /**
     * @dataProvider dpConfigs
     */
    public function testUserDetails($config, $results)
    {
        $provider = new OauthProvider($config);
        $token = new AccessToken(['access_token' => 'abc']);
        $response = [
            'user_id' => 1,
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
        ];

        $user = $provider->userDetails((object) $response, $token);

        $this->assertEquals($user->uid, $response['user_id']);
        $this->assertEquals($user->name, $response['name']);
        $this->assertEquals($user->email, $response['email']);

        // test empty values
        $user = $provider->userDetails((object) [], $token);
        $this->assertNull($user->uid);
        $this->assertNull($user->name);
        $this->assertNull($user->email);
    }

    public function dpConfigs()
    {
        return [
            [
                $this->config,
                [
                    'authurl'  => 'http://api.contentnet.com/oauth?response_type=code',
                    'tokenurl' => 'http://api.contentnet.com/oauth',
                    'userurl'  => 'http://api.contentnet.com/api/v1/user/get?access_token=',
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
