# WebMoney interfaces for Laravel 5
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