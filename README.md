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

    'environment' => env('PAYMAYA_ENVIRONMENT', PayMayaClient::ENVIRONMENT_SANDBOX),
    
    'key' => env('PAYMAYA_KEY'),

    'secret' => env('PAYMAYA_SECRET'),

    'webhooks' => [

        'checkout_success' => env('PAYMAYA_WEBHOOKS_CHECKOUT_SUCCESS'),

        'checkout_failure' => env('PAYMAYA_WEBHOOKS_CHECKOUT_FAILURE'),

        'checkout_dropout' => env('PAYMAYA_WEBHOOKS_CHECKOUT_DROPOUT'),

        'payment_success' => env('PAYMAYA_WEBHOOKS_PAYMENT_SUCCESS'),

        'payment_failed' => env('PAYMAYA_WEBHOOKS_PAYMENT_FAILED'),

        'payment_expired' => env('PAYMAYA_WEBHOOKS_PAYMENT_EXPIRED'),

    ],

];
```

## Usage

### Creating a payment

``` php
use CoreProc\PayMaya\PayMayaClient;
use CoreProc\PayMaya\Requests\Address;
use Coreproc\PaymayaLaravel\Builders\PaymayaCheckoutBuilder;
use Coreproc\PaymayaLaravel\Facades\PaymayaCheckoutClientFacade;

$checkout = PaymayaCheckoutBuilder::make()
    ->setCurrency('PHP')
    ->setItem($paymayaItemModel, 1)
    ->setDiscount(1000)
    ->setServiceCharge(1001)
    ->setShippingFee(1002)
    ->setTax(1003)
    ->setBuyerFirstName('Juan')
    ->setBuyerMiddleName('D')
    ->setBuyerLastName('Dela Cruz')
    ->setBuyerContactPhone('09171231234')
    ->setBuyerContactEmail('juan@gmail.com')
    ->setBuyerShippingAddress(Address::make()->setLine1('123 Daan')->setCity('Quezon City'))
    ->setBuyerBillingAddress(Address::make()->setLine1('456 Highway')->setCity('Makati City'))
    ->setReferenceNumber('100')
    ->setRedirectUrlSuccess('https://yoursite.com/success')
    ->setRedirectUrlFailure('https://yoursite.com/failure')
    ->setRedirectUrlCancel('https://yoursite.com/cancel')
    ->build();

$response = PaymayaCheckoutClientFacade::post($checkout);

$result = PayMayaClient::getDataFromResponse($response);

return redirect()->to($result->redirectUrl);
```

### Installing the webhooks

```
php artisan paymaya:install-webhook
```
This command will register all webhooks defined in the configuration file. To register only the
webhooks you need, remove webhooks from the array.

You can also refer to the webhook's callback URL using the route name.
```php
<?php
return [
    'webhooks' => [
        'payment_success' => 'hooks.paymaya.success',
        'payment_failed' => 'hooks.paymaya.failed',
        'payment_expired' => 'hooks.paymaya.expired',
    ],
];
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
