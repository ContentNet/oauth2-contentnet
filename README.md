# PHP OAuth2 client for ContentNet

This package provides ContentNet OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require contentnet/oauth2-contentnet
```

## Usage

Usage is the same as The League's OAuth client, using `ContentNet\OAuth2\Client\Provider\ContentNet` as the provider.

### Authorization Code Flow

```php
$provider = new ContentNet\OAuth2\Client\Provider\ContentNet([
        'clientId' => 'YOUR_CLIENT_ID',
        'clientSecret' => 'YOUR_CLIENT_SECRET',
        'redirectUri' => 'http://your-redirect-uri',
]);

$provider->testMode = true; // Allows you to work in ContentNet's Sandbox environment.

if (isset($_GET['code']) && $_GET['code']) {
    $token = $this->provider->getAccessToken('authorizaton_code', [
            'code' => $_GET['code']
    ]);

    // Returns an instance of League\OAuth2\Client\User
    $user = $this->provider->getUserDetails($token);
    $uid = $provider->getUserUid($token);
    $email = $provider->getUserEmail($token);
    $screenName = $provider->getUserScreenName($token);
}
```

### Refreshing A Token

```php
$provider = new ContentNet\OAuth2\Client\Provider\ContentNet([
        'clientId' => 'YOUR_CLIENT_ID',
        'clientSecret' => 'YOUR_CLIENT_SECRET',
        'redirectUri' => 'http://your-redirect-uri',
]);

$grant = new \League\OAuth2\Client\Grant\RefreshToken();
$token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
```
