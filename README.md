# WebMoney interfaces for Laravel 5
This package interacts with the API Webmoney

## XML-interfaces supported
-----
- X2: transferring funds from one purse to another

## Installation Via Composer
-----
1. Install [Composer](https://getcomposer.org/):
```bash
curl -sS https://getcomposer.org/installer | php
```
2. Add the dependency:
```bash
php composer.phar require pusha/laravel-webmoney
```
3. Add the service provider to `config/app.php`, within the `providers` array:
```php
    'providers' => [
        // ...
        Pusha\LaravelWebMoney\WMServiceProvider::class,
    ]
```
4. Publish the config file:
```
php artisan vendor:publish
```