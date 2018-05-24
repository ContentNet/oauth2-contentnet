<?php

namespace ContentNet\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User as User;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;
use League\OAuth2\Client\Token\AccessToken as AccessToken;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

/**
 * A client-side implementation of OAuth2 for ContentNet
 */
class ContentNet extends AbstractProvider
{
    /*
    abstract protected function createResourceOwner(array $response, AccessToken $token);
     */
    /**
     * The domain used to prefix the URLs for OAuth calls.
     */
    protected $serverDomain = 'http://api.contentnet.com';

    /**
     * {@inheritDoc}
     */
    protected function getDefaultScopes() : array
    {
        return ['charge'];
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAuthorizationUrl() : String
    {
        return $this->serverDomain . '/oauth?response_type=code';
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAccessTokenUrl(array $params) : String
    {
        return $this->serverDomain . '/oauth';
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token) : String
    {
        return sprintf('%s/api/v1/user/get?access_token=%s', $this->serverDomain, $token);
    }

    /**
     * {@inheritDoc}
     */
    public function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $this->parseErrorMessage($data),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * {@inheritDoc}
     **/
    public function createResourceOwner(array $response, AccessToken $token)
    {
        return $response;
    }
}
