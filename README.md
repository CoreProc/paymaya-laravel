# Paymaya SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coreproc/paymaya-laravel.svg?style=flat-square)](https://packagist.org/packages/coreproc/paymaya-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/coreproc/paymaya-laravel/run-tests?label=tests)](https://github.com/coreproc/paymaya-laravel/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/coreproc/paymaya-laravel.svg?style=flat-square)](https://packagist.org/packages/coreproc/paymaya-laravel)


Paymaya SDK for Laravel

## Installation

You can install the package via composer:

```bash
composer require coreproc/paymaya-laravel
```

You can publish and run the migrations with:

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Coreproc\Paymaya\PaymayaServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    

];
```

## Usage

``` php
$paymaya-laravel = new Coreproc\Paymaya();
echo $paymaya-laravel->echoPhrase('Hello, Coreproc!');
```

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Chris Bautista](https://github.com/chrisbjr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
