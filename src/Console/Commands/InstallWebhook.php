<?php

namespace Coreproc\PaymayaLaravel\Console\Commands;

use CoreProc\PayMaya\Clients\Checkout\WebhookClient;
use CoreProc\PayMaya\Requests\Checkout\Webhook;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ResponseInterface;

class InstallWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymaya:install-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs PayMaya webhooks.';

    /**
     * Execute the console command.
     *
     * @param  WebhookClient  $webhookClient
     *
     * @return void
     */
    public function handle(WebhookClient $webhookClient)
    {
        $registeredWebHooks = $this->installedWebhooks($webhookClient);

        foreach ($this->hooks() as $hook) {
            if ($installedHook = $this->getExistingHook($registeredWebHooks, $hook['name'])) {
                $webhookClient->put($this->updateRequest($installedHook));
            } else {
                $webhookClient->post($this->installRequest($hook));
            }
        }

        $this->info('Webhooks successfully installed.');
    }

    private function hooks()
    {
        return collect(Config::get('paymaya.webhooks', []))
            ->mapWithKeys(function ($callbackUrl, $name) {
                $name = strtoupper($name);
                $callbackUrl = $this->resolveUrl($callbackUrl);

                return [$name => compact('name', 'callbackUrl')];
            });
    }

    private function updateRequest(object $hook): Webhook
    {
        return Webhook::make()
            ->setId($hook->id)
            ->setName($hook->name)
            ->setCallbackUrl($this->hooks()[$hook->name]['callbackUrl']);
    }

    private function installRequest(array $hook): Webhook
    {
        return Webhook::make()
            ->setName($hook['name'])
            ->setCallbackUrl($hook['callbackUrl']);
    }

    private function installedWebhooks(WebhookClient $webhookClient): array
    {
        try {
            return $webhookClient->get();
        } catch (ClientException $exception) {
            if ($this->isFreshInstall($exception->getResponse())) {
                return [];
            }

            throw $exception;
        }
    }

    private function isFreshInstall(ResponseInterface $response): bool
    {
        $status = $response->getStatusCode();
        $response = json_decode($response->getBody()->getContents());

        return $status === 404 && $response->code === 'PY0038';
    }

    private function getExistingHook(array $registeredHooks, string $name): ?object
    {
        return collect($registeredHooks)->where('name', $name)->first();
    }

    private function resolveUrl(string $route): string
    {
        return Route::has($route) ? route($route) : (string) url($route);
    }
}
