<?php

namespace Coreproc\PaymayaLaravel;

use CoreProc\PayMaya\Clients\Checkout\CheckoutClient;
use CoreProc\PayMaya\Clients\Checkout\CustomizeClient;
use CoreProc\PayMaya\Clients\Checkout\WebhookClient;
use CoreProc\PayMaya\PayMayaClient;
use Exception;
use Illuminate\Support\ServiceProvider;

class PaymayaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/paymaya.php' => config_path('paymaya.php'),
            ], 'config');
        }
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paymaya.php', 'paymaya');

        $paymayaClient = $this->paymayaClient();

        $this->app->singleton(CheckoutClient::class, function ($app) use ($paymayaClient) {
            return new CheckoutClient($paymayaClient);
        });

        $this->app->singleton(WebhookClient::class, function ($app) use ($paymayaClient) {
            return new WebhookClient($paymayaClient);
        });

        $this->app->singleton(CustomizeClient::class, function ($app) use ($paymayaClient) {
            return new CustomizeClient($paymayaClient);
        });
    }

    /**
     * @return PayMayaClient
     * @throws Exception
     */
    protected function paymayaClient()
    {
        return new PayMayaClient(
            config('paymaya.secret'),
            config('paymaya.key'),
            config('paymaya.environment')
        );
    }
}
