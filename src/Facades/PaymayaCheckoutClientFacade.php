<?php

namespace Coreproc\PaymayaLaravel\Facades;

use CoreProc\PayMaya\Clients\Checkout\CheckoutClient;
use CoreProc\PayMaya\Requests\Checkout\Checkout;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Coreproc\Paymaya\Paymaya
 *
 * @method static post(Checkout $checkout)
 * @method static get(string $checkoutId)
 */
class PaymayaCheckoutClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CheckoutClient::class;
    }
}
