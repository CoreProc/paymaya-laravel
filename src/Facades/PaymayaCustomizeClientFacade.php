<?php

namespace Coreproc\PaymayaLaravel\Facades;

use CoreProc\PayMaya\Clients\Checkout\CustomizeClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Coreproc\Paymaya\Paymaya
 */
class PaymayaCustomizeClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomizeClient::class;
    }
}
