<?php

namespace Coreproc\PaymayaLaravel\Facades;

use CoreProc\PayMaya\Clients\Checkout\WebhookClient;
use CoreProc\PayMaya\Requests\Checkout\Webhook;
use Illuminate\Support\Facades\Facade;

/**
 * @see WebhookClient
 *
 * @method static post(Webhook $webhook)
 * @method static get()
 * @method static put(Webhook $webhook)
 *
 */
class PaymayaWebhookClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WebhookClient::class;
    }
}
