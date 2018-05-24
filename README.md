# PHP OAuth2 client for ContentNet

[![Build Status](https://travis-ci.org/ContentNet/oauth2-contentnet.svg?branch=master)](https://travis-ci.org/ContentNet/oauth2-contentnet)
[![Coverage Status](https://coveralls.io/repos/github/ContentNet/oauth2-contentnet/badge.svg?branch=master)](https://coveralls.io/github/ContentNet/oauth2-contentnet?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/ContentNet/oauth2-contentnet/master/LICENSE)

This package provides ContentNet OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require contentnet/oauth2-contentnet
```

## Usage

Usage is the same as The League's OAuth client, using `ContentNet\OAuth2\Client\Provider\ContentNet` as the provider.

### Authorization Code Flow

Follow the [Basic Usage Guide](http://oauth2-client.thephpleague.com/usage/).
