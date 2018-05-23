<?php

namespace ContentNet\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User as User;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;
use League\OAuth2\Client\Token\AccessToken as AccessToken;

/**
 * A client-side implementation of OAuth2 for ContentNet
 */
final class ContentNet extends AbstractProvider
{
    /**
     * The domain used to prefix the URLs for OAuth calls.
     */
    protected $serverDomain = 'http://api.contentnet.com';

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize() : String
    {
        return $this->serverDomain . '/oauth?response_type=code';
    }

    /**
     * Get the URL that this provider uses to request an access token.
     *
     * @return string
     */
    public function urlAccessToken() : String
    {
        return $this->serverDomain . '/oauth';
    }

    /**
     * Get the URL that this provider uses to request user details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token) : String
    {
        return sprintf('%s/api/v1/user/get?access_token=%s', $this->serverDomain, $token);
    }

    /**
     * Given an object response from the server, process the user details into a User object.
     *
     * @param object $response
     * @param AccessToken $token
     * @return User
     */
    public function userDetails($response, AccessToken $token) : User
    {
        $user = new User();
        $user->exchangeArray([
            'uid'   => isset($response->user_id) ? $response->user_id : null,
            'name'  => isset($response->name) ? $response->name : null,
            'email' => isset($response->email) ? $response->email : null
        ]);

        return $user;
    }
}
