<?php

namespace Coreproc\PaymayaLaravel\Tests\Feature\Console;

use CoreProc\PayMaya\Clients\Checkout\WebhookClient;
use CoreProc\PayMaya\Requests\Checkout\Webhook;
use Coreproc\PaymayaLaravel\PaymayaServiceProvider;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class InstallWebhookTest extends TestCase
{
    use WithFaker;

    public function getPackageProviders($app)
    {
        return [PaymayaServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('paymaya.webhooks', [
            'payment_success' => 'http://localhost/success',
            'payment_failed' => 'http://localhost/failed',
            'payment_expired' => 'http://localhost/expired',
        ]);
    }

    protected function defineRoutes($router)
    {
        $action = function () {
            return null;
        };
        $router->post('/success', $action)->name('route.success');
        $router->post('/failed', $action)->name('route.failed');
        $router->post('/expired', $action)->name('route.expired');
    }

    public function testFreshInstall()
    {
        $client = $this->mock(WebhookClient::class);
        $client->shouldReceive('get')
            ->once()
            ->andThrow($this->clientException());
        $client->shouldReceive('post')
            ->times(3)
            ->withArgs(function (Webhook $webhook) {
                return in_array([$webhook->getName(), $webhook->getCallbackUrl()], [
                    ['PAYMENT_SUCCESS', 'http://localhost/success'],
                    ['PAYMENT_FAILED', 'http://localhost/failed'],
                    ['PAYMENT_EXPIRED', 'http://localhost/expired'],
                ]);
            });

        $this->artisan('paymaya:install-webhook');
    }

    public function testUpdate()
    {
        $client = $this->mock(WebhookClient::class);

        $client->shouldReceive('get')->once()->andReturn($this->mockFetchResponse());

        $client->shouldReceive('put')->once()->withArgs($this->argsForPutHook('PAYMENT_SUCCESS'));
        $client->shouldReceive('put')->once()->withArgs($this->argsForPutHook('PAYMENT_FAILED'));
        $client->shouldReceive('put')->once()->withArgs($this->argsForPutHook('PAYMENT_EXPIRED'));

        $this->artisan('paymaya:install-webhook');
    }

    public function testInstallIfDoesNotExist()
    {
        $client = $this->mock(WebhookClient::class);

        $client->shouldReceive('get')->once()->andReturn($this->mockDifferentWebhooksResponse());

        $client->shouldReceive('post')
            ->times(3)
            ->withArgs(function (Webhook $webhook) {
                return in_array([$webhook->getName(), $webhook->getCallbackUrl()], [
                    ['PAYMENT_SUCCESS', 'http://localhost/success'],
                    ['PAYMENT_FAILED', 'http://localhost/failed'],
                    ['PAYMENT_EXPIRED', 'http://localhost/expired'],
                ]);
            });

        $this->artisan('paymaya:install-webhook');
    }

    public function testAcceptRouteNamesInConfig()
    {
        $this->app['config']->set('paymaya.webhooks', [
            'payment_success' => 'route.success',
            'payment_failed' => 'route.failed',
            'payment_expired' => 'route.expired',
        ]);

        $client = $this->mock(WebhookClient::class);
        $client->shouldReceive('get')
            ->once()
            ->andThrow($this->clientException());
        $client->shouldReceive('post')
            ->times(3)
            ->withArgs(function (Webhook $webhook) {
                return in_array([$webhook->getName(), $webhook->getCallbackUrl()], [
                    ['PAYMENT_SUCCESS', 'http://localhost/success'],
                    ['PAYMENT_FAILED', 'http://localhost/failed'],
                    ['PAYMENT_EXPIRED', 'http://localhost/expired'],
                ]);
            });

        $this->artisan('paymaya:install-webhook');
    }

    private function mockFetchResponse()
    {
        return [
            (object) ([
                'id' => 'kqwlejhjwqejklwqhekhwqe',
                'name' => 'PAYMENT_SUCCESS',
                'callback_url' => $this->faker()->url,
            ]),
            (object) ([
                'id' => 'qwjeokqwjeklwqje',
                'name' => 'PAYMENT_FAILED',
                'callback_url' => $this->faker()->url,
            ]),
            (object) ([
                'id' => 'lqwjewjqekljwqewk',
                'name' => 'PAYMENT_EXPIRED',
                'callback_url' => $this->faker()->url,
            ]),
        ];
    }

    private function mockDifferentWebhooksResponse()
    {
        return [
            (object) ([
                'id' => 'kqwlejhjwqejklwqhekhwqe',
                'name' => 'CHECKOUT_SUCCESS',
                'callback_url' => $this->faker()->url,
            ]),
            (object) ([
                'id' => 'qwjeokqwjeklwqje',
                'name' => 'CHECKOUT_FAILED',
                'callback_url' => $this->faker()->url,
            ]),
            (object) ([
                'id' => 'lqwjewjqekljwqewk',
                'name' => 'CHECKOUT_CANCELLED',
                'callback_url' => $this->faker()->url,
            ]),
        ];
    }

    private function argsForPutHook(string $hook)
    {
        $hookObject = collect($this->mockFetchResponse())->where('name', $hook)->first();

        return function (Webhook $webhook) use ($hookObject, $hook) {
            return $webhook->getId() === $hookObject->id &&
                $webhook->getName() === $hookObject->name &&
                $webhook->getCallbackUrl() === $this->hook($hook);
        };
    }

    private function hook(string $hook)
    {
        return [
            'PAYMENT_SUCCESS' => 'http://localhost/success',
            'PAYMENT_FAILED' => 'http://localhost/failed',
            'PAYMENT_EXPIRED' => 'http://localhost/expired',
        ][$hook];
    }

    private function clientException(): ClientException
    {
        return new ClientException(
            '404 Not Found',
            new Request('GET', 'https://pg-sandbox.paymaya.com/checkout/v1/webhooks'),
            new Response(404, [], json_encode(['code' => 'PY0038', 'message' => 'Webhook does not exist.']))
        );
    }
}
