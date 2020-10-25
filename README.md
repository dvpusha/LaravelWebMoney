[![Latest Stable Version](https://img.shields.io/packagist/v/pusha/laravel-webmoney.svg)](https://packagist.org/packages/pusha/laravel-webmoney)
[![Total Downloads](https://img.shields.io/packagist/dt/pusha/laravel-webmoney.svg)](https://packagist.org/packages/pusha/laravel-webmoney)
[![License](https://img.shields.io/github/license/pusha/laravel-webmoney.svg)](https://packagist.org/packages/pusha/laravel-webmoney)

# WebMoney interfaces for Laravel
This package interacts with the API Webmoney

## XML-interfaces supported
- X2: transferring funds from one purse to another

## Installation Via Composer

1. Add the dependency:

    ```bash
    composer require pusha/laravel-webmoney
    ```

2. Add the service provider to `config/app.php`, within the `providers` array:

    ```php
    'providers' => [
        // ...
        Pusha\LaravelWebMoney\WMServiceProvider::class,
    ]
    ```
    
3. Publish the config file:
    ```
    php artisan vendor:publish
    ```


## Examples
- [Interface X2](https://github.com/dvpusha/LaravelWebMoney/wiki/Interface-X2)
