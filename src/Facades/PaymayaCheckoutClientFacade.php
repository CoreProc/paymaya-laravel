<?php

namespace Coreproc\PaymayaLaravel\Facades;

use CoreProc\PayMaya\Clients\Checkout\CheckoutClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Coreproc\Paymaya\Paymaya
 */
class PaymayaCheckoutClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CheckoutClient::class;
    }
}
