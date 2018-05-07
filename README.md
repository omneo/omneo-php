<img src="logo.png" height="40" />

![GitHub release](https://img.shields.io/github/release/omneo/omneo-php.svg) [![Documentation](https://img.shields.io/badge/documentation-GitBook-blue.svg)](https://omneo.gitbook.io/omneo-php)

Omneo PHP is a PHP client library for the Omneo API service. It allows developers to easily integrate Omneo with their PHP applications.

## Prerequisites

Before being able to use the package, you will need to get your hands on the following.

- __Omneo domain__ - This takes the form of `example.omneoapp.io` and is where your Omneo installation lives. If you login to your dashboard at `foo.dashboard.omneo.io`, your Omneo domain is `foo.omneoapp.io`.

- __Omneo token__ - This is the secure token which identifies you to the API. It can be generated from the Omneo dashboard and is associated with your user account. It is advisable that you create a separate Omneo user specifically for your integration.

- __Shared secret__ - You only need this if you plan on accepting incoming webhook and target requests from Omneo. If so, you will need to validate that it's actually Omneo sending the request by generating and comparing a signature using the shared secret.

## Installation

Installation is performed via [Composer](https://getcomposer.org/).

```
composer require omneo/omneo-php
```

## Quickstart

### Plain PHP

```php
// Get credentials, you should store these outside of your codebase
$domain = 'foo.omneoapp.io';
$token = '...';
​
// Instantiate the Omneo client
$omneo = new Omneo\Client($domain, $token);
​
// Optional, add the shared secret for verifying webhooks and targets
$omneo->setSecret('horsebatterystaple');
​
// Use the client to communicate with omneo
$profiles = $omneo->profiles()->browse();
```

### Laravel

> This package utilises Laravel [Package Discovery](https://laravel.com/docs/5.6/packages#package-discovery) which means you do not need to register any service providers after installation, just type hint and go!

This package comes batteries-included for usage with Laravel. To begin, install the package using Composer and update the following files.

##### config/services.php

```php
'omneo' => [
    'domain' => env('OMNEO_DOMAIN'),
    'token' => env('OMNEO_TOKEN'),
    'secret' => env('OMNEO_SECRET')
]
```

##### .env

```ini
OMNEO_DOMAIN=
OMNEO_TOKEN=
OMNEO_SECRET=
```

Now that you're authentication details are set, you can utilise the Omneo package anywhere in your application that has access to the [Service Container](https://laravel.com/docs/5.6/container).

```php
class FooController extends Controller
{    
    public function getProfiles(Omneo\Client $omneo)
    {
        // Using typehints
        $profiles = $omneo->profiles()->browse();
        
        // Using the app() method
        $profiles = app(Omneo\Client::class)->profiles()->browse();
    }
}
```
